/**
 * SkyWorks GSAP Animations
 * Cannabis retro-futurism aesthetic animations
 */

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { TextPlugin } from 'gsap/TextPlugin';

gsap.registerPlugin(ScrollTrigger, TextPlugin);

class SkyWorksAnimations {
    constructor() {
        this.init();
    }

    init() {
        // Check if animations are enabled
        const animationsEnabled = document.body.classList.contains('animations-enabled') || 
                                 !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        
        if (!animationsEnabled) return;

        this.initHeroAnimations();
        this.initScrollAnimations();
        this.initNeonEffects();
        this.initParticleSystem();
        this.initHoverAnimations();
        
        console.log('SkyWorks animations initialized');
    }

    // Hero section animations
    initHeroAnimations() {
        const hero = document.querySelector('.hero-section');
        if (!hero) return;

        const title = hero.querySelector('.hero-title');
        const subtitle = hero.querySelector('.hero-subtitle');
        const cta = hero.querySelector('.hero-cta');
        const background = hero.querySelector('.hero-background');

        // Timeline for hero entrance
        const heroTl = gsap.timeline();

        // Background fade in
        if (background) {
            heroTl.from(background, {
                opacity: 0,
                scale: 1.1,
                duration: 2,
                ease: "power2.out"
            });
        }

        // Title animation with neon effect
        if (title) {
            heroTl.from(title, {
                opacity: 0,
                y: 50,
                duration: 1.2,
                ease: "power3.out",
                onComplete: () => {
                    this.animateNeonText(title);
                }
            }, "-=1.5");
        }

        // Subtitle typewriter effect
        if (subtitle) {
            heroTl.from(subtitle, {
                opacity: 0,
                y: 30,
                duration: 0.8,
                ease: "power2.out"
            }, "-=0.5");

            // Typewriter effect
            const subtitleText = subtitle.textContent;
            subtitle.textContent = '';
            heroTl.to(subtitle, {
                text: subtitleText,
                duration: 2,
                ease: "none"
            }, "-=0.3");
        }

        // CTA button entrance
        if (cta) {
            heroTl.from(cta, {
                opacity: 0,
                scale: 0.8,
                duration: 0.6,
                ease: "back.out(1.7)"
            }, "-=0.5");
        }
    }

    // Scroll-triggered animations
    initScrollAnimations() {
        // Fade in elements on scroll
        gsap.utils.toArray('.fade-in').forEach(element => {
            gsap.from(element, {
                opacity: 0,
                y: 50,
                duration: 1,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: element,
                    start: "top 80%",
                    end: "bottom 20%",
                    toggleActions: "play none none reverse"
                }
            });
        });

        // Slide in from left
        gsap.utils.toArray('.slide-in-left').forEach(element => {
            gsap.from(element, {
                opacity: 0,
                x: -100,
                duration: 1,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: element,
                    start: "top 80%",
                    toggleActions: "play none none reverse"
                }
            });
        });

        // Slide in from right
        gsap.utils.toArray('.slide-in-right').forEach(element => {
            gsap.from(element, {
                opacity: 0,
                x: 100,
                duration: 1,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: element,
                    start: "top 80%",
                    toggleActions: "play none none reverse"
                }
            });
        });

        // Scale on scroll
        gsap.utils.toArray('.scale-on-scroll').forEach(element => {
            gsap.from(element, {
                scale: 0.5,
                duration: 1,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: element,
                    start: "top 80%",
                    toggleActions: "play none none reverse"
                }
            });
        });

        // Parallax effect
        gsap.utils.toArray('.parallax').forEach(element => {
            gsap.to(element, {
                yPercent: -50,
                ease: "none",
                scrollTrigger: {
                    trigger: element,
                    start: "top bottom",
                    end: "bottom top",
                    scrub: true
                }
            });
        });
    }

    // Neon text animation
    animateNeonText(element) {
        const colors = ['#00ff88', '#ff0080', '#ffff00', '#00d4ff'];
        let colorIndex = 0;

        setInterval(() => {
            gsap.to(element, {
                textShadow: `0 0 5px ${colors[colorIndex]}, 
                            0 0 10px ${colors[colorIndex]}, 
                            0 0 15px ${colors[colorIndex]}, 
                            0 0 20px ${colors[colorIndex]}`,
                duration: 0.5,
                ease: "power2.inOut"
            });
            
            colorIndex = (colorIndex + 1) % colors.length;
        }, 3000);
    }

    // Neon effects for interactive elements
    initNeonEffects() {
        // Buttons
        document.querySelectorAll('.btn-neon').forEach(button => {
            button.addEventListener('mouseenter', () => {
                gsap.to(button, {
                    boxShadow: "0 0 5px var(--skyworks-primary), 0 0 10px var(--skyworks-primary), 0 0 15px var(--skyworks-primary)",
                    scale: 1.05,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });

            button.addEventListener('mouseleave', () => {
                gsap.to(button, {
                    boxShadow: "none",
                    scale: 1,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });
        });

        // Cards
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                gsap.to(card, {
                    y: -10,
                    boxShadow: "0 10px 30px rgba(0, 255, 136, 0.3)",
                    duration: 0.3,
                    ease: "power2.out"
                });
            });

            card.addEventListener('mouseleave', () => {
                gsap.to(card, {
                    y: 0,
                    boxShadow: "none",
                    duration: 0.3,
                    ease: "power2.out"
                });
            });
        });
    }

    // Particle system for background
    initParticleSystem() {
        const particleContainer = document.querySelector('.particles');
        if (!particleContainer) return;

        const particleCount = 50;
        const particles = [];

        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.cssText = `
                position: absolute;
                width: 2px;
                height: 2px;
                background: var(--skyworks-primary);
                border-radius: 50%;
                opacity: ${Math.random() * 0.5 + 0.2};
            `;
            
            particleContainer.appendChild(particle);
            particles.push(particle);

            // Random initial position
            gsap.set(particle, {
                x: Math.random() * window.innerWidth,
                y: Math.random() * window.innerHeight
            });

            // Floating animation
            this.animateParticle(particle);
        }
    }

    animateParticle(particle) {
        gsap.to(particle, {
            x: Math.random() * window.innerWidth,
            y: Math.random() * window.innerHeight,
            duration: Math.random() * 20 + 10,
            ease: "none",
            repeat: -1,
            yoyo: true
        });

        // Opacity pulsing
        gsap.to(particle, {
            opacity: Math.random() * 0.8 + 0.2,
            duration: Math.random() * 3 + 1,
            ease: "sine.inOut",
            repeat: -1,
            yoyo: true
        });
    }

    // Hover animations
    initHoverAnimations() {
        // Image hover effects
        document.querySelectorAll('.hover-glow').forEach(element => {
            element.addEventListener('mouseenter', () => {
                gsap.to(element, {
                    filter: "drop-shadow(0 0 20px var(--skyworks-primary))",
                    duration: 0.3
                });
            });

            element.addEventListener('mouseleave', () => {
                gsap.to(element, {
                    filter: "none",
                    duration: 0.3
                });
            });
        });

        // Text reveal on hover
        document.querySelectorAll('.text-reveal').forEach(element => {
            const text = element.textContent;
            element.innerHTML = text.split('').map(char => 
                `<span class="char">${char}</span>`
            ).join('');

            element.addEventListener('mouseenter', () => {
                gsap.from(element.querySelectorAll('.char'), {
                    opacity: 0,
                    y: 20,
                    duration: 0.5,
                    stagger: 0.02,
                    ease: "power2.out"
                });
            });
        });
    }

    // Method to create custom scroll-triggered animations
    createScrollAnimation(element, animation, triggerOptions = {}) {
        return gsap.fromTo(element, 
            animation.from || {}, 
            {
                ...animation.to,
                scrollTrigger: {
                    trigger: element,
                    start: "top 80%",
                    end: "bottom 20%",
                    toggleActions: "play none none reverse",
                    ...triggerOptions
                }
            }
        );
    }

    // Method to add glitch effect
    addGlitchEffect(element, intensity = 1) {
        const glitchTl = gsap.timeline({ repeat: -1, yoyo: true });
        
        glitchTl.to(element, {
            skewX: Math.random() * 20 - 10,
            skewY: Math.random() * 5 - 2.5,
            scaleX: 1 + (Math.random() * 0.1 - 0.05) * intensity,
            scaleY: 1 + (Math.random() * 0.1 - 0.05) * intensity,
            duration: 0.1,
            ease: "power2.inOut"
        })
        .to(element, {
            skewX: 0,
            skewY: 0,
            scaleX: 1,
            scaleY: 1,
            duration: Math.random() * 2 + 1,
            ease: "power2.inOut"
        });
    }
}

// Initialize animations when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.skyWorksAnimations = new SkyWorksAnimations();
});

export default SkyWorksAnimations;