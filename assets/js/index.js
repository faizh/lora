
(function () {
    "use strict";

    /**
     * Easy selector helper function
     */
    const select = (el, all = false) => {
        el = el.trim()
        if (all) {
            return [...document.querySelectorAll(el)]
        } else {
            return document.querySelector(el)
        }
    }

    /**
     * Easy event listener function
     */
    const on = (type, el, listener, all = false) => {
        if (all) {
            select(el, all).forEach(e => e.addEventListener(type, listener))
        } else {
            select(el, all).addEventListener(type, listener)
        }
    }

    /**
     * Easy on scroll event listener 
     */
    const onscroll = (el, listener) => {
        el.addEventListener('scroll', listener)
    }

    /**
     * Scrolls to an element with header offset
     */
    const scrollto = (el) => {
        let header = select('#header')
        let offset = header.offsetHeight

        if (!header.classList.contains('header-scrolled')) {
            offset -= 10
        }

        let elementPos = select(el).offsetTop
        window.scrollTo({
            top: elementPos - offset,
            behavior: 'smooth'
        })
    }

    /**
     * Toggle .header-scrolled class to #header when page is scrolled
     */
    let selectHeader = select('#header')
    if (selectHeader) {
        const headerScrolled = () => {
            if (window.scrollY > 100) {
                selectHeader.classList.add('header-scrolled')
            } else {
                selectHeader.classList.remove('header-scrolled')
            }
        }
        window.addEventListener('load', headerScrolled)
        onscroll(document, headerScrolled)
    }


    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: -6.995420249600775,
                lng: 110.43340624630443
            },
            zoom: 14,
            mapId: 'f16ec96aa2289a5f',
            mapTypeControl: false,
            fullscreenControl: false,
            streetViewControl: false
        });



        const info1 = new google.maps.InfoWindow({
            content: "Lokasi Lora 1 berjarak 1.5 km dari lokasi Lora 2",
        })

        const info2 = new google.maps.InfoWindow({
            content: "Lokasi Lora 2 berjarak 1.5 km dari lokasi Lora 1",
        })

        const mark1 = new google.maps.Marker({
            position: {
                lat: -6.989495,
                lng: 110.427199,
            },
            map,
            label: {
                text: "Lokasi Lora 1",
                className: "marker-label",
            },
            animation: google.maps.Animation.DROP,
        });

        const mark2 = new google.maps.Marker({
            position: {
                lat: -7.003952701075967,
                lng: 110.42983021471943
            },
            map,
            label: {
                text: "Lokasi Lora 2",
                className: "marker-label",
            },
            animation: google.maps.Animation.DROP,
        });

        mark1.addListener("click", () => {
            map.panTo(mark1.getPosition());
            info1.open(map, mark1);
        });

        mark2.addListener("click", () => {
            map.panTo(mark2.getPosition());
            info2.open(map, mark2);
        });

    }

    /**
     * Animation on scroll
     */
    function aos_init() {
        AOS.init({
            duration: 1000,
            easing: "ease-in-out",
            once: true,
            mirror: false
        });
    }
    window.addEventListener('load', () => {
        aos_init();

        setTimeout(() => {
            initMap();
        }, 500);
    });

})();