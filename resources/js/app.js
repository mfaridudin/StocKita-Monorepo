import './bootstrap';
import { gsap } from "gsap";
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById("sidebar");
    const toggleMobile = document.getElementById("toggleSidebar");
    const toggleCollapse = document.getElementById("toggleCollapse");
    const overlay = document.getElementById("overlay");
    const texts = document.querySelectorAll(".sidebar-text");
    const main = document.getElementById("mainContent");

    let isMobileOpen = false;
    let collapsed = false;
    let hoverTimeout;

    gsap.set(main, { marginLeft: 256 });
    // dekstop
    const iconMenu = document.getElementById("iconMenu");
    const iconArrow = document.getElementById("iconArrow");

    toggleCollapse?.addEventListener("click", () => {
        collapsed = !collapsed;

        if (collapsed) {
            collapseSidebar();

            gsap.to(iconMenu, { opacity: 0, scale: 0.6, duration: 0.2 });
            gsap.to(iconArrow, { opacity: 1, scale: 1, duration: 0.2 });

        } else {
            expandSidebar();

            gsap.to(iconArrow, { opacity: 0, scale: 0.6, duration: 0.2 });
            gsap.to(iconMenu, { opacity: 1, scale: 1, duration: 0.2 });
        }
    });

    function collapseSidebar() {
        gsap.to(texts, {
            opacity: 0,
            x: -10,
            duration: 0.15,
            stagger: 0.02
        });

        gsap.to(sidebar, {
            width: 80,
            duration: 0.2,
            ease: "power3.inOut"
        });

        gsap.to(main, {
            marginLeft: 80,
            duration: 0.2,
            ease: "power3.inOut"
        });
    }

    function expandSidebar() {
        gsap.to(sidebar, {
            width: 256,
            duration: 0.2,
            ease: "power3.inOut"
        });

        gsap.to(main, {
            marginLeft: 256,
            duration: 0.2,
            ease: "power3.inOut"
        });

        gsap.fromTo(texts,
            { opacity: 0, x: -10 },
            {
                opacity: 1,
                x: 0,
                duration: 0.2,
                delay: 0.1,
                stagger: 0.03
            }
        );
    }

    // hover
    sidebar.addEventListener("mouseenter", () => {
        if (!collapsed) return;


        gsap.to(iconArrow, { opacity: 0, scale: 0.6, duration: 0.2 });
        gsap.to(iconMenu, { opacity: 1, scale: 1, duration: 0.2 });


        clearTimeout(hoverTimeout);

        hoverTimeout = setTimeout(() => {
            expandSidebar();
        }, 100);
    });

    sidebar.addEventListener("mouseleave", () => {
        if (!collapsed) return;

        gsap.to(iconMenu, { opacity: 0, scale: 0.6, duration: 0.2 });
        gsap.to(iconArrow, { opacity: 1, scale: 1, duration: 0.2 });

        hoverTimeout = setTimeout(() => {
            collapseSidebar();
        }, 150);
    });
});