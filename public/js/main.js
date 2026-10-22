// Initialize AOS
AOS.init({
    duration: 800,
    easing: 'slide'
});

(function($) {
    "use strict";

    // Stellar Parallax
    $(window).stellar({
        responsive: true,
        parallaxBackgrounds: true,
        parallaxElements: true,
        horizontalScrolling: false,
        hideDistantElements: false,
        scrollProperty: 'scroll'
    });

    var fullHeight = function() {
        $('.js-fullheight').css('height', $(window).height());
        $(window).resize(function() {
            $('.js-fullheight').css('height', $(window).height());
        });
    };
    fullHeight();

    // Loader
    var loader = function() {
        setTimeout(function() {
            if ($('#ftco-loader').length > 0) {
                $('#ftco-loader').removeClass('show');
            }
        }, 1);
    };
    loader();

    // Scrollax
    $.Scrollax();

    // Carousels
    var carousel = function() {
        $('.home-slider').owlCarousel({
            loop: true,
            autoplay: true,
            margin: 0,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            nav: false,
            autoplayHoverPause: false,
            items: 1,
            navText: ["<span class='ion-md-arrow-back'></span>", "<span class='ion-chevron-right'></span>"],
            responsive: { 0: { items: 1 }, 600: { items: 1 }, 1000: { items: 1 } }
        });
        $('.carousel-testimony').owlCarousel({
            autoplay: true,
            center: true,
            loop: true,
            items: 1,
            margin: 30,
            stagePadding: 0,
            nav: false,
            navText: ['<span class="ion-ios-arrow-back">', '<span class="ion-ios-arrow-forward">'],
            responsive: { 0: { items: 1 }, 600: { items: 1 }, 1000: { items: 2 } }
        });
    };
    carousel();

    // Navbar Dropdowns
    $('nav .dropdown').hover(function() {
        $(this).addClass('show').find('> a').attr('aria-expanded', true);
        $(this).find('.dropdown-menu').addClass('show');
    }, function() {
        $(this).removeClass('show').find('> a').attr('aria-expanded', false);
        $(this).find('.dropdown-menu').removeClass('show');
    });

    // Scroll Logic
    var scrollWindow = function() {
        $(window).scroll(function() {
            var $w = $(this), st = $w.scrollTop(), navbar = $('.ftco_navbar');
            if (st > 150) { if (!navbar.hasClass('scrolled')) navbar.addClass('scrolled'); }
            if (st < 150) { if (navbar.hasClass('scrolled')) navbar.removeClass('scrolled sleep'); }
            if (st > 350) { if (!navbar.hasClass('awake')) navbar.addClass('awake'); }
            if (st < 350) { if (navbar.hasClass('awake')) navbar.removeClass('awake').addClass('sleep'); }
        });
    };
    scrollWindow();

    // Counter Animation
    var counter = function() {
        if ($('#section-counter').length > 0) {
            $('#section-counter').waypoint(function(direction) {
                if (direction === 'down' && !$(this.element).hasClass('ftco-animated')) {
                    var comma_step = $.animateNumber.numberStepFactories.separator(',')
                    $('.number').each(function() {
                        $(this).animateNumber({ number: $(this).data('number'), numberStep: comma_step }, 7000);
                    });
                }
            }, { offset: '95%' });
        }
    }
    counter();

    // Content Animations
    var contentWayPoint = function() {
        $('.ftco-animate').waypoint(function(direction) {
            if (direction === 'down' && !$(this.element).hasClass('ftco-animated')) {
                $(this.element).addClass('item-animate');
                setTimeout(function() {
                    $('body .ftco-animate.item-animate').each(function(k) {
                        var el = $(this);
                        setTimeout(function() {
                            var effect = el.data('animate-effect');
                            if (effect === 'fadeIn') el.addClass('fadeIn ftco-animated');
                            else if (effect === 'fadeInLeft') el.addClass('fadeInLeft ftco-animated');
                            else if (effect === 'fadeInRight') el.addClass('fadeInRight ftco-animated');
                            else el.addClass('fadeInUp ftco-animated');
                            el.removeClass('item-animate');
                        }, k * 50, 'easeInOutExpo');
                    });
                }, 100);
            }
        }, { offset: '95%' });
    };
    contentWayPoint();

    // Date/Time Picker
    if ($('.appointment_date').length > 0 && typeof $.fn.datepicker !== 'undefined') {
        $('.appointment_date').datepicker({ 'format': 'm/d/yyyy', 'autoclose': true });
    }
    if ($('.appointment_time').length > 0 && typeof $.fn.timepicker !== 'undefined') {
        $('.appointment_time').timepicker();
    }

    // Job Filter Logic
    var jobFilter = function() {
        $('.filter-btn').on('click', function(e) {
            e.preventDefault();
            $('.filter-btn').removeClass('active btn-primary').addClass('btn-outline-primary');
            $(this).removeClass('btn-outline-primary').addClass('active btn-primary');
            var filterValue = $(this).attr('data-filter');
            if (filterValue === 'all') {
                $('.filter-item').fadeIn(300).addClass('ftco-animated').css('opacity', 1);
            } else {
                $('.filter-item').each(function() {
                    if ($(this).attr('data-category') === filterValue) $(this).fadeIn(300).addClass('ftco-animated').css('opacity', 1);
                    else $(this).hide();
                });
            }
            if (typeof AOS !== 'undefined') AOS.refresh();
            $(window).trigger('resize');
        });
    };
    jobFilter();

    // Application Status Toggle
    $(document).on('change', '.status-select', function() {
        const appId = this.getAttribute('data-app-id');
        const timeDiv = document.getElementById('timeInput' + appId);
        if (this.value === 'Interview Set') {
            if (timeDiv) timeDiv.classList.remove('d-none');
        } else {
            if (timeDiv) timeDiv.classList.add('d-none');
        }
    });

})(jQuery);


/** 
 * GLOBALLY ACCESSIBLE FUNCTIONS
 */

// Experience Logic
function editExperience(data) {
    document.getElementById('expModalTitle').innerText = 'Edit Experience';
    document.getElementById('edit_experience_id').value = data.id; 
    document.getElementById('exp_title').value = data.title;
    document.getElementById('exp_company').value = data.company;
    document.getElementById('exp_duration').value = data.duration;
    document.getElementById('exp_desc').value = data.description;
}

function clearExperienceModal() {
    const titleEl = document.getElementById('expModalTitle');
    if(titleEl) titleEl.innerText = 'Add Experience';
    
    const idEl = document.getElementById('edit_experience_id');
    if(idEl) idEl.value = ''; 

    const form = document.getElementById('expForm');
    if(form) form.reset(); 
}

// Education Logic
function editEducation(data) {
    const titleEl = document.getElementById('eduModalTitle');
    const idEl = document.getElementById('edit_education_id');
    
    if(titleEl) titleEl.innerText = 'Edit Education';
    if(idEl) idEl.value = data.id; 
    
    document.getElementById('edu_degree').value = data.degree;
    document.getElementById('edu_institution').value = data.institution;
    document.getElementById('edu_duration').value = data.duration;
}

function clearEducationModal() {
    const titleEl = document.getElementById('eduModalTitle');
    const idEl = document.getElementById('edit_education_id');
    const form = document.getElementById('eduForm');

    if(titleEl) titleEl.innerText = 'Add Education';
    if(idEl) idEl.value = ''; 
    if(form) form.reset(); 
}

// Job Logic
function editJob(job) {
    $('#jobModalTitle').text('Edit Job Circular');
    $('#jobSubmitBtn').text('Update Circular');

    $('#edit_job_id').val(job.id);
    $('#job_title').val(job.title);
    $('#job_type').val(job.job_type);
    $('#job_openings').val(job.openings);
    $('#job_educations').val(job.educations);
    $('#job_category').val(job.category);
    $('#job_skills').val(job.skills_needed);
    $('#job_description').val(job.description);
    $('#job_details').val(job.job_details);
}

function clearJobModal() {
    $('#jobModalTitle').text('Post New Job Circular');
    $('#jobSubmitBtn').text('Save Circular');
    $('#edit_job_id').val(''); 
    
    const form = $('#jobForm');
    if(form.length > 0) {
        form[0].reset();
    }
}

/** 
 * PASSWORD VISIBILITY TOGGLE
 */
$(document).on('click', '.toggle-password', function() {
    // Get the target input field ID from the data-target attribute
    const targetId = $(this).attr('data-target');
    const inputField = $(targetId);
    
    // Find the icon element inside the clicked button
    const icon = $(this).find('i');
    
    // Toggle the input type and the FontAwesome classes
    if (inputField.attr('type') === 'password') {
        inputField.attr('type', 'text');
        icon.removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
        inputField.attr('type', 'password');
        icon.removeClass('fa-eye-slash').addClass('fa-eye');
    }
});