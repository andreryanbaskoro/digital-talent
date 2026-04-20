<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <meta name="description" content="Digital Talent Hub – Temukan ribuan lowongan pekerjaan terbaik di seluruh Indonesia. Cari pekerjaan impianmu sekarang.">
    <title>Digital Talent Hub – Disnaker Jayapura</title>


    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        inter: ['Inter', 'sans-serif']
                    },

                    colors: {
                        primary: {
                            DEFAULT: '#2563EB', // BLUE 600 (main)
                            50: '#EFF6FF',
                            100: '#DBEAFE',
                            200: '#BFDBFE',
                            300: '#93C5FD',
                            400: '#60A5FA',
                            500: '#3B82F6',
                            600: '#2563EB',
                            700: '#1D4ED8',
                            800: '#1E40AF',
                            900: '#1E3A8A'
                        },

                        accent: {
                            DEFAULT: '#06B6D4', // cyan modern accent
                            light: '#67E8F9'
                        },
                    },

                    animation: {
                        'fade-in-up': 'fadeInUp .6s ease both',
                        'pulse-slow': 'pulse 3s infinite',
                    },

                    keyframes: {
                        fadeInUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(24px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },
                    },
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* blob background */
        .blob {
            position: absolute;
            border-radius: 9999px;
            filter: blur(90px);
            opacity: .25;
            pointer-events: none;
        }

        /* HERO lebih modern */
        .hero-gradient {
            background: linear-gradient(135deg,
                    #0f172a 0%,
                    #1e293b 40%,
                    #1d4ed8 100%);
        }

        /* glass effect */
        .glass {
            background: rgba(255, 255, 255, .08);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, .12);
        }

        /* job card */
        .job-card {
            transition: all .25s ease;
        }

        .job-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 25px 50px rgba(37, 99, 235, .18);
        }

        /* scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        /* badge helper */
        .badge {
            @apply inline-flex items-center gap-1 text-xs font-medium px-2.5 py-0.5 rounded-full;
        }
    </style>

    @stack('styles')
</head>