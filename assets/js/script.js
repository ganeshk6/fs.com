document.addEventListener('DOMContentLoaded', function() {
    const submenuLinks = document.querySelectorAll('.submenu-link');
    submenuLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            const targetId = this.getAttribute('data-target');
            const allSubmenus = document.querySelectorAll('.submenu-content');
            allSubmenus.forEach(submenu => {
                if (submenu.id === targetId) {
                    submenu.style.display = 'grid';
                    link.classList.add('active-submenu');
                } else {
                    submenu.style.display = 'none';
                }
            });
        });

        link.addEventListener('mouseleave', function() {
            link.classList.remove('active-submenu');
        });
    });

    const dropdown = document.querySelector('.dropdown');
    const dropdownContent = document.querySelector('.dropdown-content');
    const navLink = dropdown.querySelector('.nav-link');

    dropdown.addEventListener('mouseenter', function() {
        dropdownContent.style.display = 'flex';
        navLink.classList.add('active-dropdown');
    });

    dropdown.addEventListener('mouseleave', function() {
        dropdownContent.style.display = 'none';
        navLink.classList.remove('active-dropdown');
    });
});


// swiper 

$(document).ready(function(){
    $('.slick-slider').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        infinite: true,
        dots: true,
        arrows: true,
        prevArrow: '<button type="button" class="slick-prev">Previous</button>',
        nextArrow: '<button type="button" class="slick-next">Next</button>',
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
});
$(document).ready(function(){
    $('.slick-slider3').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        infinite: true,
        dots: true,
        arrows: true,
        prevArrow: '<button type="button" class="slick-prev">Previous</button>',
        nextArrow: '<button type="button" class="slick-next">Next</button>',
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
});