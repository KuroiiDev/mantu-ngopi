<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    @vite('resources/css/app.css')

    <title>Mantu | Login</title>

    <style>
        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-card {
            animation: fadeSlideUp 0.9s ease-out;
        }
    </style>
    <script src="https://kit.fontawesome.com/a740d9e852.js" crossorigin="anonymous"></script>
</head>

<body class="min-h-screen flex items-center justify-center relative overflow-hidden">

    <div class="absolute inset-0 bg-cover bg-center"
        style="background-image: url('https://images.wallpapersden.com/image/download/glowing-purple-cloud-art_bWVqaWuUmZqaraWkpJRmbmdlrWZlbWU.jpg');">
    </div>

    <div class="absolute inset-0 bg-black/20 backdrop-blur-md"></div>

    <div class="relative z-10 w-full max-w-4xl h-[600px] 
                bg-[#1a1029]/90 border border-purple-400/30 
                shadow-[0_0_40px_rgba(192,132,252,0.3)] 
                rounded-3xl overflow-hidden flex animate-card">

        {{-- Login Form di Kiri --}}
        <div class="w-1/2 p-12 flex flex-col justify-center text-gray-200">

            <div class="flex items-center gap-3 px-6 py-5 bg-gradient-to-r from-gray-900 to-gray-1000 
                        rounded-xl mb-10 shadow-lg shadow-purple-500/30">
                <i class="fa-solid fa-mug-hot text-purple-400 text-4xl"></i>
                <span class="font-bold text-white text-4xl">Mantu-Ngopi</span>
            </div>

            <h2 class="text-2xl font-bold mb-8 text-lilac-300 text-purple-300 tracking-wide">
                Welcome Back!
            </h2>

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Username --}}
                <div>
                    <label class="block text-sm mb-2 text-purple-200">
                        Username
                    </label>
                    <input type="text" name="username" value="{{ old('username') }}" class="w-full px-4 py-3 bg-[#140c22] border border-purple-400/40 
                               rounded-xl focus:outline-none 
                               focus:ring-2 focus:ring-purple-300 
                               focus:border-purple-300
                               transition duration-300">

                    @error('username')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm mb-2 text-purple-200">
                        Password
                    </label>

                    <div class="relative">
                        <input type="password" id="password" name="password" class="w-full px-4 py-3 bg-[#140c22] border border-purple-400/40 
                                   rounded-xl focus:outline-none 
                                   focus:ring-2 focus:ring-purple-300 
                                   focus:border-purple-300
                                   transition duration-300">

                        <button type="button" onclick="togglePassword(event)"
                            class="absolute right-4 top-4 text-sm text-purple-300 hover:text-purple-100 transition">
                            <i class="fa-regular fa-eye" id="eye-icon"></i>
                        </button>
                    </div>

                    @error('password')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Login Button --}}
                <div class="pt-4">
                    <button type="submit" class="w-full py-3 rounded-xl font-semibold tracking-wide
                               bg-gradient-to-r from-purple-500 to-fuchsia-500
                               hover:from-purple-400 hover:to-fuchsia-400
                               transition duration-300
                               shadow-lg shadow-purple-500/30">
                        Login
                    </button>
                </div>
            </form>
        </div>

        {{-- Image di Kanan --}}
        <div class="w-1/2 relative">
            <img src="https://img.freepik.com/free-photo/black-tea-with-herbs-coaster-purple-background_23-2147975770.jpg?semt=ais_rp_progressive&w=740&q=80"
                alt="Login Image" class="w-full h-full object-cover brightness-75">
        </div>

    </div>

    <script>
        function togglePassword(e) {
            const password = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            const button = e.target;

            if (password.type === "password") {
                password.type = "text";
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                password.type = "password";
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>

</body>

</html>