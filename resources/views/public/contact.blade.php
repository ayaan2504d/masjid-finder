@extends('layouts.public')

@section('title', 'Contact Us')

@section('content')
<section class="py-5">
  <div class="container">
    <div class="text-center mb-5">
      <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-semibold mb-3"><i class="fas fa-envelope me-2"></i>Contact us</span>
      <h1 class="display-6 fw-bold text-success mb-3">We are here to help with mosque and timing enquiries.</h1>
      <p class="lead text-secondary mb-0">Use the contact form below for general questions, updates, or feedback about the website and the masjid directory.</p>
    </div>

    <div class="row g-4 align-items-stretch">
      <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
          <div class="card-body p-4 p-lg-5">
            <h2 class="h4 fw-bold text-success mb-3"><i class="fas fa-address-book me-2"></i>Quick contact information</h2>
            <p class="text-secondary mb-4">Reach out to our support team for updates, corrections, or any question about the platform.</p>

            <div class="d-flex align-items-start gap-3 mb-3 pb-3 border-bottom">
              <div class="info-icon bg-success-subtle text-success"><i class="fas fa-map-marker-alt"></i></div>
              <div>
                <h3 class="h6 fw-bold text-success mb-1">Address</h3>
                <p class="text-secondary mb-0">Karachi, Sindh, Pakistan</p>
              </div>
            </div>

            <div class="d-flex align-items-start gap-3 mb-3 pb-3 border-bottom">
              <div class="info-icon bg-success-subtle text-success"><i class="fas fa-phone"></i></div>
              <div>
                <h3 class="h6 fw-bold text-success mb-1">Phone</h3>
                <p class="text-secondary mb-0">+92 21 1234567</p>
              </div>
            </div>

            <div class="d-flex align-items-start gap-3 mb-4">
              <div class="info-icon bg-success-subtle text-success"><i class="fas fa-envelope"></i></div>
              <div>
                <h3 class="h6 fw-bold text-success mb-1">Email</h3>
                <p class="text-secondary mb-0">info@masjidlocator.com</p>
              </div>
            </div>

            <div class="rounded-4 overflow-hidden border border-success-subtle" style="min-height: 220px;">
              <div id="contactMap" style="height: 220px; width: 100%;"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
          <div class="card-body p-4 p-lg-5">
            <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
              <div>
                <h2 class="h4 fw-bold text-success mb-1"><i class="fas fa-paper-plane me-2"></i>Send a message</h2>
                <p class="text-secondary mb-0">You can also share general suggestions, corrections, or partnership ideas here.</p>
              </div>
              <span class="badge bg-light text-success border border-success-subtle">Response within 24 hours</span>
            </div>

            @if(session('status'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            @endif

            @if($errors->any())
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Some details need attention:</strong>
                <ul class="mb-0 mt-2 ps-3">
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            @endif

            <form method="POST" action="{{ route('contact.store') }}" class="contact-form">
              @csrf
              <div class="mb-3">
                <label class="form-label" for="name">Full name</label>
                <input id="name" type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Ahmad Ali" required>
              </div>
              <div class="mb-3">
                <label class="form-label" for="email">Email address</label>
                <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="e.g. ahmad@example.com" required>
              </div>
              <div class="mb-3">
                <label class="form-label" for="subject">Subject</label>
                <input id="subject" type="text" name="subject" class="form-control" value="{{ old('subject') }}" placeholder="Timing correction or general enquiry">
              </div>
              <div class="mb-3">
                <label class="form-label" for="message">Message</label>
                <textarea id="message" name="message" class="form-control" rows="5" placeholder="Write your message here..." required>{{ old('message') }}</textarea>
              </div>
              <button type="submit" class="btn btn-primary-custom w-100"><i class="fas fa-paper-plane me-2"></i>Send message</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const map = L.map('contactMap', { zoomControl: false }).setView([24.8607, 67.0011], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    L.marker([24.8607, 67.0011], { icon: L.divIcon({ html: '<i class="fas fa-mosque text-success"></i>', className: 'contact-map-icon', iconSize: [28, 28] }) })
      .addTo(map)
      .bindPopup('Masjid Locator Headquarters');
  });
</script>
@endsection
