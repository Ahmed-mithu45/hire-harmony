<footer class="ftco-footer ftco-bg-dark ftco-section">
    <div class="container">
        <div class="row mb-5">
            {{-- Contact Info --}}
            <div class="col-md-6 col-lg-3">
                <div class="ftco-footer-widget mb-5">
                    <h2 class="ftco-heading-2">Have Questions?</h2>
                    <div class="block-23 mb-3">
                        <ul>
                            <li><span class="fa fa-map-marker mr-3"></span><span class="text">Dhaka, Bangladesh</span>
                            </li>
                            <li><a href="tel:+8801568505325"><span class="fa fa-phone mr-3"></span><span
                                        class="text">+880 1568-505325</span></a></li>
                            <li><a href="mailto:hireharmony@gmail.com"><span class="fa fa-envelope mr-3"></span><span
                                        class="text">hireharmony@gmail.com</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Services Links --}}
            <div class="col-md-6 col-lg-3">
                <div class="ftco-footer-widget mb-5">
                    <h2 class="ftco-heading-2">Services</h2>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/jobs') }}" class="py-2 d-block"><span
                                    class="fa fa-chevron-right mr-2"></span>Browse Jobs</a></li>
                        <li><a href="{{ url('/login') }}" class="py-2 d-block"><span
                                    class="fa fa-chevron-right mr-2"></span>Post a Circular</a></li>
                        <li><a href="{{ url('/login') }}" class="py-2 d-block"><span
                                    class="fa fa-chevron-right mr-2"></span>Create Unique ID</a></li>
                        <li><a href="#" class="py-2 d-block"><span class="fa fa-chevron-right mr-2"></span>Career
                                Advice</a></li>
                    </ul>
                </div>
            </div>

            {{-- Navigation Links --}}
            <div class="col-md-6 col-lg-3">
                <div class="ftco-footer-widget mb-5 ml-md-4">
                    <h2 class="ftco-heading-2">Links</h2>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/') }}" class="py-2 d-block"><span
                                    class="fa fa-chevron-right mr-2"></span>Home</a></li>
                        <li><a href="{{ url('/about') }}" class="py-2 d-block"><span
                                    class="fa fa-chevron-right mr-2"></span>About Us</a></li>
                        <li><a href="#" class="py-2 d-block"><span
                                    class="fa fa-chevron-right mr-2"></span>Partners</a></li>
                        <li><a href="#" class="py-2 d-block"><span
                                    class="fa fa-chevron-right mr-2"></span>Contact</a></li>
                    </ul>
                </div>
            </div>

            {{-- Newsletter & Social --}}
            <div class="col-md-6 col-lg-3">
                <div class="ftco-footer-widget mb-5">
                    <h2 class="ftco-heading-2">Stay Updated</h2>
                    <p>Get notified about the jobs every week.</p>
                    <form action="#" class="subscribe-form">
                        @csrf
                        <div class="form-group">
                            <input type="email" class="form-control mb-2 text-center"
                                placeholder="Enter email address" required>
                            <input type="submit" value="Subscribe" class="form-control submit px-3">
                        </div>
                    </form>
                </div>
                <div class="ftco-footer-widget mb-5">
                    <h2 class="ftco-heading-2 mb-0">Connect With Us</h2>
                    <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-3">
                        <li class="ftco-animate"><a href="#"><span class="fa fa-twitter"></span></a></li>
                        <li class="ftco-animate"><a href="#"><span class="fa fa-facebook"></span></a></li>
                        <li class="ftco-animate"><a href="#"><span class="fa fa-instagram"></span></a></li>
                        <li class="ftco-animate"><a href="#"><span class="fa fa-linkedin"></span></a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-center">
                <p>
                    {{-- Automated Year Helper --}}
                    Copyright &copy; {{ date('Y') }} All rights reserved | <strong>Hire Harmony</strong>
                </p>
            </div>
        </div>
    </div>
</footer>
