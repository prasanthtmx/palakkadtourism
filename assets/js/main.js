// Main JavaScript for Palakkad Tourism

// Google Analytics 4 — useful events only (consent-aware)
(function() {
    function track(name, params) {
        if (typeof window.pkdTrack === 'function') {
            window.pkdTrack(name, params);
        } else if (typeof gtag === 'function') {
            gtag('event', name, params || {});
        }
    }

    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (!link || !link.href) {
            return;
        }
        const href = link.href;
        const isDownload = /\.(pdf|doc|docx|xls|xlsx|zip|rar)(?:$|[?#])/i.test(href);

        if (href.indexOf('mailto:') === 0) {
            track('email_click', { transport_type: 'beacon' });
            return;
        }
        if (href.indexOf('tel:') === 0) {
            track('phone_click', { transport_type: 'beacon' });
            return;
        }
        if (isDownload) {
            const fileName = href.split('/').pop().split('?')[0];
            track('file_download', {
                file_name: fileName,
                file_extension: (fileName.split('.').pop() || '').toLowerCase(),
                transport_type: 'beacon'
            });
            return;
        }
        if (link.hostname && link.hostname !== window.location.hostname) {
            track('click', {
                event_category: 'outbound',
                link_url: href,
                transport_type: 'beacon'
            });
        }
    });

    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (!form || form.tagName !== 'FORM' || form.id === 'pkd-chat-form') {
            return;
        }

        const searchInput = form.querySelector('input[name="search"], input[name="q"], input[type="search"]');
        if (searchInput && searchInput.value.trim()) {
            track('search', { search_term: searchInput.value.trim() });
            return;
        }

        track('form_submit', {
            form_id: form.id || '',
            form_name: form.getAttribute('name') || form.id || ''
        });
    });

    const scrollThresholds = [25, 50, 75, 90];
    const trackedThresholds = {};
    window.addEventListener('scroll', function() {
        const height = document.documentElement.scrollHeight;
        if (!height) {
            return;
        }
        const scrollPercent = Math.round(((window.scrollY + window.innerHeight) / height) * 100);
        scrollThresholds.forEach(function(threshold) {
            if (scrollPercent >= threshold && !trackedThresholds[threshold]) {
                trackedThresholds[threshold] = true;
                track('scroll', { percent_scrolled: threshold });
            }
        });
    }, { passive: true });

    document.addEventListener('change', function(e) {
        const select = e.target;
        if (select && select.tagName === 'SELECT' && (select.name === 'type' || select.name === 'category')) {
            track('filter_change', {
                filter_name: select.name,
                filter_value: select.value
            });
        }
    });

    document.addEventListener('play', function(e) {
        if (e.target && e.target.tagName === 'VIDEO') {
            track('video_start', {
                video_title: e.target.getAttribute('title') || e.target.getAttribute('aria-label') || 'Video'
            });
        }
    }, true);
})();

// Weather API Configuration
// Note: Replace 'YOUR_API_KEY' with your OpenWeatherMap API key
// Get free API key from: https://openweathermap.org/api
const WEATHER_API_KEY = 'YOUR_API_KEY'; // Replace with your API key
const WEATHER_CITY = 'Palakkad';
const WEATHER_COUNTRY = 'IN';

// Fetch Weather Data
function fetchWeather() {
    const weatherWidget = document.getElementById('weatherWidget');
    
    if (!weatherWidget) return;
    
    // Using OpenWeatherMap API (free tier available)
    // Alternative: You can also use a free weather widget service
    const apiUrl = `https://api.openweathermap.org/data/2.5/weather?q=${WEATHER_CITY},${WEATHER_COUNTRY}&appid=${WEATHER_API_KEY}&units=metric`;
    
    // If no API key, use a fallback method with a free weather service
    if (WEATHER_API_KEY === 'YOUR_API_KEY') {
        // Using wttr.in as a free alternative (no API key needed)
        fetch(`https://wttr.in/${WEATHER_CITY}?format=j1`)
            .then(response => response.json())
            .then(data => {
                displayWeather(data);
            })
            .catch(error => {
                console.error('Weather fetch error:', error);
                weatherWidget.innerHTML = '<div class="weather-error"><i class="bi bi-exclamation-triangle"></i> Weather unavailable</div>';
            });
    } else {
        // Use OpenWeatherMap API
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                if (data.cod === 200) {
                    displayOpenWeatherMapData(data);
                } else {
                    throw new Error('Weather data not available');
                }
            })
            .catch(error => {
                console.error('Weather fetch error:', error);
                weatherWidget.innerHTML = '<div class="weather-error"><i class="bi bi-exclamation-triangle"></i> Weather unavailable</div>';
            });
    }
}

// Display weather from wttr.in
function displayWeather(data) {
    const weatherWidget = document.getElementById('weatherWidget');
    if (!weatherWidget || !data.current_condition) return;
    
    const current = data.current_condition[0];
    const location = data.nearest_area[0].areaName[0].value;
    
    const temp = current.temp_C;
    const desc = current.weatherDesc[0].value;
    const humidity = current.humidity;
    const windSpeed = current.windspeedKmph;
    
    const weatherIcon = getWeatherIcon(desc, current.is_day === 'yes');
    
    weatherWidget.innerHTML = `
        <div class="weather-content">
            <div class="weather-location">
                <i class="bi bi-geo-alt"></i> ${location}
            </div>
            <div class="weather-icon">${weatherIcon}</div>
            <div class="weather-temp">${temp}°C</div>
            <div class="weather-desc">${desc}</div>
            <div class="weather-details">
                <span><i class="bi bi-droplet"></i> ${humidity}%</span>
                <span><i class="bi bi-wind"></i> ${windSpeed} km/h</span>
            </div>
        </div>
    `;
}

// Display weather from OpenWeatherMap
function displayOpenWeatherMapData(data) {
    const weatherWidget = document.getElementById('weatherWidget');
    if (!weatherWidget) return;
    
    const temp = Math.round(data.main.temp);
    const desc = data.weather[0].description;
    const humidity = data.main.humidity;
    const windSpeed = Math.round(data.wind.speed * 3.6); // Convert m/s to km/h
    const city = data.name;
    
    const weatherIcon = getWeatherIcon(desc, true);
    
    weatherWidget.innerHTML = `
        <div class="weather-content">
            <div class="weather-location">
                <i class="bi bi-geo-alt"></i> ${city}
            </div>
            <div class="weather-icon">${weatherIcon}</div>
            <div class="weather-temp">${temp}°C</div>
            <div class="weather-desc">${desc}</div>
            <div class="weather-details">
                <span><i class="bi bi-droplet"></i> ${humidity}%</span>
                <span><i class="bi bi-wind"></i> ${windSpeed} km/h</span>
            </div>
        </div>
    `;
}

// Get weather icon based on description
function getWeatherIcon(desc, isDay) {
    const descLower = desc.toLowerCase();
    
    if (descLower.includes('sun') || descLower.includes('clear')) {
        return '<i class="bi bi-sun-fill text-warning"></i>';
    } else if (descLower.includes('cloud')) {
        return '<i class="bi bi-cloud-fill text-secondary"></i>';
    } else if (descLower.includes('rain') || descLower.includes('drizzle')) {
        return '<i class="bi bi-cloud-rain-fill text-primary"></i>';
    } else if (descLower.includes('thunder') || descLower.includes('storm')) {
        return '<i class="bi bi-cloud-lightning-rain-fill text-danger"></i>';
    } else if (descLower.includes('mist') || descLower.includes('fog')) {
        return '<i class="bi bi-cloud-fog-fill text-muted"></i>';
    } else if (descLower.includes('snow')) {
        return '<i class="bi bi-snow text-info"></i>';
    } else {
        return '<i class="bi bi-cloud-sun text-secondary"></i>';
    }
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Load weather data
    fetchWeather();
    
    // Refresh weather every 30 minutes
    setInterval(fetchWeather, 1800000);
    
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href.length > 1) {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });

    // Add animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe cards for animation
    document.querySelectorAll('.card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
});

// Form validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    }
}

// Image preview function
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
            document.getElementById(previewId).style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Confirm delete
function confirmDelete(message) {
    return confirm(message || 'Are you sure you want to delete this item?');
}

// Show loading state
function showLoading(button) {
    if (button) {
        button.disabled = true;
        button.innerHTML = '<span class="loading"></span> Processing...';
    }
}

// Hide loading state
function hideLoading(button, originalText) {
    if (button) {
        button.disabled = false;
        button.innerHTML = originalText || 'Submit';
    }
}

