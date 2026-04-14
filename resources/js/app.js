import './bootstrap';
import { gsap } from "gsap";
import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Swal = Swal;
window.Alpine = Alpine;
Alpine.start();

document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");
    const main = document.getElementById("mainContent");

    const toggleSidebar = document.getElementById("toggleSidebar");
    const closeSidebar = document.getElementById("closeSidebar");
    const toggleCollapse = document.getElementById("toggleCollapse");

    const titles = document.querySelectorAll(".sidebar-title");
    const texts = document.querySelectorAll(".sidebar-text");
    const groups = document.querySelectorAll(".group-item");

    const iconMenu = document.getElementById("iconMenu");
    const iconArrow = document.getElementById("iconArrow");

    const body = document.body;

    let collapsed = false;
    let hoverTimeout;


    gsap.set(sidebar, { x: "-100%" });
    gsap.set(overlay, { opacity: 0, display: "none" });

    // mobile
    function openSidebar() {
        gsap.to(sidebar, {
            x: 0,
            duration: 0.1,
            ease: "power3.out"
        });

        gsap.to(overlay, {
            opacity: 1,
            display: "block",
            duration: 0.2
        });

        gsap.fromTo(texts,
            { opacity: 0, x: -10 },
            {
                opacity: 1,
                x: 0,
                duration: 0.2,
                delay: 0.1,
                stagger: 0.05
            }
        );

        body.classList.add("overflow-hidden");
    }

    function closeSidebarFn() {
        gsap.to(sidebar, {
            x: "-100%",
            duration: 0.1,
            ease: "power3.inOut"
        });

        gsap.to(overlay, {
            opacity: 0,
            duration: 0.2,
            onComplete: () => {
                overlay.style.display = "none";
            }
        });

        gsap.to(texts, {
            opacity: 0,
            x: -10,
            duration: 0.05,
            stagger: 0.01
        });

        body.classList.remove("overflow-hidden");
    }

    toggleSidebar?.addEventListener("click", openSidebar);
    closeSidebar?.addEventListener("click", closeSidebarFn);
    overlay?.addEventListener("click", closeSidebarFn);

    // dekstop
    let mm = gsap.matchMedia();

    mm.add("(min-width: 1024px)", () => {

        gsap.set(sidebar, { x: 0 });
        gsap.set(main, { paddingLeft: 256 });

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
                paddingLeft: 80,
                duration: 0.2,
                ease: "power3.inOut"
            });

            gsap.to(groups, {
                marginTop: 0,
                duration: 0.2,
                ease: "power2.out"
            });

            gsap.to(titles, {
                opacity: 0,
                height: 0,
                marginTop: 0,
                duration: 0.15,
                stagger: 0.02
            });
        }

        function expandSidebar() {
            gsap.to(sidebar, {
                width: 256,
                duration: 0.2,
                ease: "power3.inOut"
            });

            gsap.to(main, {
                paddingLeft: 256,
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

            gsap.to(groups, {
                marginTop: 12,
                duration: 0.2,
                ease: "power2.out"
            });

            gsap.fromTo(titles,
                { opacity: 0, height: 0 },
                {
                    opacity: 1,
                    height: "auto",
                    duration: 0.2,
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

        return () => { };
    });

});