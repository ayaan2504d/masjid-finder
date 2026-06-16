/**
 * Masjid Locator — Core JavaScript
 * GPS, Map, Countdown, Favorites, Filtering
 */

// ═══════════════════════════════════════════════════════════════
// GPS LOCATION MODULE
// ═══════════════════════════════════════════════════════════════
const GPS = {
    lat: null,
    lng: null,
    ready: false,

    /**
     * Get user's current position
     * @returns {Promise<{lat: number, lng: number}>}
     */
    getLocation() {
        return new Promise((resolve, reject) => {
            if (!navigator.geolocation) {
                reject(new Error('Geolocation not supported'));
                return;
            }
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    this.lat = pos.coords.latitude;
                    this.lng = pos.coords.longitude;
                    this.ready = true;
                    window.currentUserLat = this.lat;
                    window.currentUserLng = this.lng;
                    resolve({ lat: this.lat, lng: this.lng });
                },
                (err) => {
                    console.warn('GPS Error:', err.message);
                    reject(err);
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 300000 }
            );
        });
    },

    buildDirectionsUrl(destinationLat, destinationLng, originLat = this.lat, originLng = this.lng) {
        const userLat = parseFloat(originLat);
        const userLng = parseFloat(originLng);
        const masjidLat = parseFloat(destinationLat);
        const masjidLng = parseFloat(destinationLng);

        if (!Number.isFinite(userLat) || !Number.isFinite(userLng) || !Number.isFinite(masjidLat) || !Number.isFinite(masjidLng)) {
            return `https://www.google.com/maps/search/?api=1&query=${masjidLat},${masjidLng}`;
        }

        return `https://www.google.com/maps/dir/${userLat.toFixed(8)},${userLng.toFixed(8)}/${masjidLat.toFixed(8)},${masjidLng.toFixed(8)}`;
    },

    refreshDirectionsLinks() {
        document.querySelectorAll('[data-directions-link="true"]').forEach(link => {
            const lat = link.dataset.destLat;
            const lng = link.dataset.destLng;
            link.href = this.buildDirectionsUrl(lat, lng);
        });
    }
};

// ═══════════════════════════════════════════════════════════════
// HAVERSINE DISTANCE (CLIENT-SIDE)
// ═══════════════════════════════════════════════════════════════
function haversineDistance(lat1, lon1, lat2, lon2) {
    const R = 6371;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat / 2) ** 2 +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon / 2) ** 2;
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return (R * c).toFixed(2);
}

// ═══════════════════════════════════════════════════════════════
// PRAYER COUNTDOWN TIMER
// ═══════════════════════════════════════════════════════════════
const PrayerCountdown = {
    timerInterval: null,
    activeMasjid: null,
    prayerOrder: ['fajr', 'zuhr', 'asr', 'maghrib', 'isha'],
    prayerLabels: {
        fajr: 'Fajr',
        zuhr: 'Zuhr',
        asr: 'Asr',
        maghrib: 'Maghrib',
        isha: 'Isha'
    },

    /**
     * Start countdown for the selected masjid.
     * Accepts the current masjid object and recalculates the next prayer every second.
     */
    start(masjid, displayElementId) {
        const el = document.getElementById(displayElementId);
        if (!el || !masjid) return;

        if (this.timerInterval) clearInterval(this.timerInterval);
        this.activeMasjid = masjid;

        const tick = () => {
            const next = this.getNextPrayer(this.activeMasjid, new Date());
            if (!next) return;
            const diff = Math.max(0, next.targetDate.getTime() - Date.now());
            const h = Math.floor(diff / 3600000);
            const m = Math.floor((diff % 3600000) / 60000);
            const s = Math.floor((diff % 60000) / 1000);
            const hoursEl = el.querySelector('.countdown-hours');
            const minsEl = el.querySelector('.countdown-minutes');
            const secsEl = el.querySelector('.countdown-seconds');
            const nameEl = document.getElementById('countdownPrayerName') || el.querySelector('.countdown-prayer-name');

            if (hoursEl) hoursEl.textContent = String(h).padStart(2, '0');
            if (minsEl) minsEl.textContent = String(m).padStart(2, '0');
            if (secsEl) secsEl.textContent = String(s).padStart(2, '0');
            if (nameEl) nameEl.textContent = next.name;
        };

        tick();
        this.timerInterval = setInterval(tick, 1000);
    },

    getNextPrayer(masjid, now = new Date()) {
        if (!masjid) return null;

        for (const key of this.prayerOrder) {
            const time = masjid[key];
            const targetDate = this.timeToDate(time, now);
            if (targetDate && targetDate > now) {
                return {
                    key,
                    name: this.prayerLabels[key],
                    time,
                    targetDate,
                    current: this.getCurrentPrayer(masjid, now)
                };
            }
        }

        const fajrTomorrow = this.timeToDate(masjid.fajr, now);
        if (!fajrTomorrow) return null;
        fajrTomorrow.setDate(fajrTomorrow.getDate() + 1);
        return {
            key: 'fajr',
            name: 'Fajr',
            time: masjid.fajr,
            targetDate: fajrTomorrow,
            current: this.getCurrentPrayer(masjid, now)
        };
    },

    getCurrentPrayer(masjid, now = new Date()) {
        let current = null;
        for (const key of this.prayerOrder) {
            const targetDate = this.timeToDate(masjid[key], now);
            if (targetDate && targetDate <= now) {
                current = { key, name: this.prayerLabels[key], time: masjid[key] };
            }
        }
        return current;
    },

    timeToDate(time, baseDate = new Date()) {
        if (!time || typeof time !== 'string') return null;
        const parts = time.split(':').map(Number);
        if (parts.length < 2 || !Number.isFinite(parts[0]) || !Number.isFinite(parts[1])) return null;
        const target = new Date(baseDate);
        target.setHours(parts[0], parts[1], 0, 0);
        return target;
    },

    stop() {
        if (this.timerInterval) clearInterval(this.timerInterval);
    }
};

// ═══════════════════════════════════════════════════════════════
// MAP HELPERS
// ═══════════════════════════════════════════════════════════════
const MapHelper = {
    map: null,
    markers: null,
    userMarker: null,
    baseUrl: '/Masjid',

    /**
     * Custom mosque icon
     */
    getMosqueIcon() {
        return L.divIcon({
            className: 'custom-mosque-marker',
            html: '<div class="mosque-marker-inner"><i class="fas fa-mosque"></i></div>',
            iconSize: [40, 40],
            iconAnchor: [20, 40],
            popupAnchor: [0, -40]
        });
    },

    /**
     * User location icon
     */
    getUserIcon() {
        return L.divIcon({
            className: 'custom-user-marker',
            html: '<div class="user-marker-inner"><div class="user-marker-pulse"></div></div>',
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });
    },

    /**
     * Initialize a Leaflet map
     */
    init(containerId, lat = 31.5204, lng = 74.3587, zoom = 12) {
        this.map = L.map(containerId, {
            zoomControl: true,
            scrollWheelZoom: true
        }).setView([lat, lng], zoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a>',
            maxZoom: 19
        }).addTo(this.map);

        this.markers = L.markerClusterGroup({
            maxClusterRadius: 50,
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false
        });
        this.map.addLayer(this.markers);

        return this.map;
    },

    /**
     * Add masjid markers from JSON data
     */
    addMasjidMarkers(masjids, userLat = null, userLng = null) {
        this.markers.clearLayers();
        masjids.forEach(m => {
            let distText = '';
            if (userLat && userLng) {
                const dist = haversineDistance(userLat, userLng, m.latitude, m.longitude);
                distText = `<br><strong>📏 ${dist} km away</strong>`;
            }
            const popup = `
                <div class="map-popup">
                    <h6 class="mb-1"><i class="fas fa-mosque me-1"></i> ${m.name}</h6>
                    <span class="badge bg-${m.sect === 'Sunni' ? 'success' : 'info'} mb-1">${m.sect}</span>
                    <p class="mb-1 small">${m.address}</p>
                    ${distText}
                    <a href="${this.baseUrl}/masjids/masjid-details.php?id=${m.id}" class="btn btn-sm btn-success mt-2 w-100">
                        View Details <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            `;
            const marker = L.marker([m.latitude, m.longitude], { icon: this.getMosqueIcon() })
                           .bindPopup(popup);
            this.markers.addLayer(marker);
        });
    },

    /**
     * Add user location marker
     */
    addUserMarker(lat, lng) {
        if (this.userMarker) this.map.removeLayer(this.userMarker);
        this.userMarker = L.marker([lat, lng], { icon: this.getUserIcon() })
            .bindPopup('<strong>📍 Your Location</strong>')
            .addTo(this.map);
    },

    /**
     * Filter markers by distance radius
     */
    filterByRadius(masjids, userLat, userLng, radiusKm) {
        if (!radiusKm || radiusKm === 'all') {
            this.addMasjidMarkers(masjids, userLat, userLng);
            return;
        }
        const filtered = masjids.filter(m => {
            const dist = haversineDistance(userLat, userLng, m.latitude, m.longitude);
            return parseFloat(dist) <= parseFloat(radiusKm);
        });
        this.addMasjidMarkers(filtered, userLat, userLng);
    }
};

// ═══════════════════════════════════════════════════════════════
// FAVORITES (localStorage)
// ═══════════════════════════════════════════════════════════════
const Favorites = {
    KEY: 'masjid_favorites',

    getAll() {
        return JSON.parse(localStorage.getItem(this.KEY) || '[]');
    },

    isFavorite(id) {
        return this.getAll().includes(parseInt(id));
    },

    toggle(id) {
        id = parseInt(id);
        let favs = this.getAll();
        if (favs.includes(id)) {
            favs = favs.filter(f => f !== id);
        } else {
            favs.push(id);
        }
        localStorage.setItem(this.KEY, JSON.stringify(favs));
        return favs.includes(id);
    },

    updateButton(btnEl, id) {
        if (!btnEl) return;
        if (this.isFavorite(id)) {
            btnEl.innerHTML = '<i class="fas fa-heart"></i> Favorited';
            btnEl.classList.add('btn-danger');
            btnEl.classList.remove('btn-outline-danger');
        } else {
            btnEl.innerHTML = '<i class="far fa-heart"></i> Add to Favorites';
            btnEl.classList.remove('btn-danger');
            btnEl.classList.add('btn-outline-danger');
        }
    }
};

// ═══════════════════════════════════════════════════════════════
// SECT FILTER (for card grids)
// ═══════════════════════════════════════════════════════════════
function filterBySect(sect) {
    const cards = document.querySelectorAll('[data-sect]');
    cards.forEach(card => {
        if (sect === 'all' || card.dataset.sect === sect) {
            card.style.display = '';
            card.style.animation = 'fadeInUp 0.4s ease';
        } else {
            card.style.display = 'none';
        }
    });

    // Update active filter button
    document.querySelectorAll('.sect-filter-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.filter === sect) btn.classList.add('active');
    });
}

// ═══════════════════════════════════════════════════════════════
// UTILITIES
// ═══════════════════════════════════════════════════════════════
function formatTime12h(time) {
    if (!time) return 'N/A';
    const [h, m] = time.split(':');
    const hour = parseInt(h);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const h12 = hour % 12 || 12;
    return `${h12}:${m} ${ampm}`;
}

// Navbar scroll effect
window.addEventListener('scroll', () => {
    const navbar = document.getElementById('mainNavbar');
    if (navbar) {
        navbar.classList.toggle('navbar-scrolled', window.scrollY > 50);
    }
});

// Smooth scroll for anchor links
document.addEventListener('DOMContentLoaded', () => {
    GPS.refreshDirectionsLinks();

    document.addEventListener('click', (event) => {
        const link = event.target.closest('[data-directions-link="true"]');
        if (!link) return;

        const openDirections = (lat, lng) => {
            const url = GPS.buildDirectionsUrl(link.dataset.destLat, link.dataset.destLng, lat, lng);
            link.href = url;
            window.open(url, '_blank', 'noopener');
        };

        if (GPS.ready) {
            event.preventDefault();
            openDirections(GPS.lat, GPS.lng);
            return;
        }

        event.preventDefault();
        GPS.getLocation()
            .then(pos => {
                GPS.refreshDirectionsLinks();
                openDirections(pos.lat, pos.lng);
            })
            .catch(() => {
                alert('Please allow location access so Google Maps can start directions from your current GPS position.');
            });
    });

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
});
