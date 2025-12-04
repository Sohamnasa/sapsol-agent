<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resume Analyzer</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'media', 
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'sans-serif'],
                    },
                    colors: {
                        laravel: '#F53003', 
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col font-sans antialiased selection:bg-[#FF2D20] selection:text-white">

    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 flex justify-end">
        @if (Route::has('login'))
            <nav class="flex items-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm transition-colors">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm transition-colors">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm transition-colors">
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <div class="flex items-center justify-center w-full lg:grow">
        <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row shadow-2xl rounded-lg overflow-hidden">
            
            <div class="flex-1 p-6 lg:p-12 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none border-r border-gray-100 dark:border-[#222]">
                
                <h1 class="text-3xl font-semibold mb-2 text-laravel">Resume Analyzer</h1>
                <p class="mb-8 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    Optimize your career path. Upload your resume to extract key skills, identify gaps, and receive tailored learning tips.
                </p>

                <form id="analyzeForm" class="mb-8">
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Upload Resume (PDF/TXT)</label>
                    <div class="flex gap-2">
                        <input type="file" id="resume" name="resume" class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-red-50 file:text-red-700
                            hover:file:bg-red-100
                            border border-gray-300 dark:border-gray-700 rounded-md p-1 bg-gray-50 dark:bg-[#202020]" required>
                    </div>
                    
                    <button type="submit" id="submitBtn" class="mt-4 w-full bg-[#FF2D20] hover:bg-[#d61f15] text-white font-semibold py-2 px-4 rounded transition-all duration-200 shadow-md">
                        Analyze Resume
                    </button>
                </form>

                <div id="output" class="hidden animate-fade-in">
                    <div class="border-t border-gray-200 dark:border-gray-800 pt-6">
                        <div class="mb-4">
                            <h3 class="text-xs uppercase tracking-widest text-gray-500 dark:text-gray-400 font-bold mb-1">Suggested Role</h3>
                            <p id="role" class="text-xl font-medium text-gray-900 dark:text-white"></p>
                        </div>
                        
                        <div class="mb-5">
                            <h3 class="text-xs uppercase tracking-widest text-gray-500 dark:text-gray-400 font-bold mb-2">Skill Gaps</h3>
                            <div id="skill_gaps" class="flex flex-wrap gap-2"></div>
                        </div>

                        <div>
                            <h3 class="text-xs uppercase tracking-widest text-gray-500 dark:text-gray-400 font-bold mb-2">Learning Plan</h3>
                            <ul id="learning_tips" class="list-none space-y-2 text-sm text-gray-600 dark:text-gray-300"></ul>
                        </div>
                    </div>
                </div>

            </div>

            <div class="bg-[#fff2f2] dark:bg-[#1D0002] relative lg:w-[438px] w-full shrink-0 flex items-center justify-center overflow-hidden">
                <svg class="w-full h-full text-[#F53003] dark:text-[#F61500]" viewBox="0 0 438 104" fill="none" xmlns="http://www.w3.org/2000/svg" style="height: 100%; object-fit: cover;">
                     <path d="M17.2036 -3H0V102.197H49.5189V86.7187H17.2036V-3Z" fill="currentColor" />
                     <path d="M110.256 41.6337C108.061 38.1275 104.945 35.3731 100.905 33.3681C96.8667 31.3647 92.8016 30.3618 88.7131 30.3618C83.4247 30.3618 78.5885 31.3389 74.201 33.2923C69.8111 35.2456 66.0474 37.928 62.9059 41.3333C59.7643 44.7401 57.3198 48.6726 55.5754 53.1293C53.8287 57.589 52.9572 62.274 52.9572 67.1813C52.9572 72.1925 53.8287 76.8995 55.5754 81.3069C57.3191 85.7173 59.7636 89.6241 62.9059 93.0293C66.0474 96.4361 69.8119 99.1155 74.201 101.069C78.5885 103.022 83.4247 103.999 88.7131 103.999C92.8016 103.999 96.8667 102.997 100.905 100.994C104.945 98.9911 108.061 96.2359 110.256 92.7282V102.195H126.563V32.1642H110.256V41.6337ZM108.76 75.7472C107.762 78.4531 106.366 80.8078 104.572 82.8112C102.776 84.8161 100.606 86.4183 98.0637 87.6206C95.5202 88.823 92.7004 89.4238 89.6103 89.4238C86.5178 89.4238 83.7252 88.823 81.2324 87.6206C78.7388 86.4183 76.5949 84.8161 74.7998 82.8112C73.004 80.8078 71.6319 78.4531 70.6856 75.7472C69.7356 73.0421 69.2644 70.1868 69.2644 67.1821C69.2644 64.1758 69.7356 61.3205 70.6856 58.6154C71.6319 55.9102 73.004 53.5571 74.7998 51.5522C76.5949 49.5495 78.738 47.9451 81.2324 46.7427C83.7252 45.5404 86.5178 44.9396 89.6103 44.9396C92.7012 44.9396 95.5202 45.5404 98.0637 46.7427C100.606 47.9451 102.776 49.5487 104.572 51.5522C106.367 53.5571 107.762 55.9102 108.76 58.6154C109.756 61.3205 110.256 64.1758 110.256 67.1821C110.256 70.1868 109.756 73.0421 108.76 75.7472Z" fill="currentColor" />
                     <path d="M242.805 41.6337C240.611 38.1275 237.494 35.3731 233.455 33.3681C229.416 31.3647 225.351 30.3618 221.262 30.3618C215.974 30.3618 211.138 31.3389 206.75 33.2923C202.36 35.2456 198.597 37.928 195.455 41.3333C192.314 44.7401 189.869 48.6726 188.125 53.1293C186.378 57.589 185.507 62.274 185.507 67.1813C185.507 72.1925 186.378 76.8995 188.125 81.3069C189.868 85.7173 192.313 89.6241 195.455 93.0293C198.597 96.4361 202.361 99.1155 206.75 101.069C211.138 103.022 215.974 103.999 221.262 103.999C225.351 103.999 229.416 102.997 233.455 100.994C237.494 98.9911 240.611 96.2359 242.805 92.7282V102.195H259.112V32.1642H242.805V41.6337ZM241.31 75.7472C240.312 78.4531 238.916 80.8078 237.122 82.8112C235.326 84.8161 233.156 86.4183 230.614 87.6206C228.07 88.823 225.251 89.4238 222.16 89.4238C219.068 89.4238 216.275 88.823 213.782 87.6206C211.289 86.4183 209.145 84.8161 207.35 82.8112C205.554 80.8078 204.182 78.4531 203.236 75.7472C202.286 73.0421 201.814 70.1868 201.814 67.1821C201.814 64.1758 202.286 61.3205 203.236 58.6154C204.182 55.9102 205.554 53.5571 207.35 51.5522C209.145 49.5495 211.288 47.9451 213.782 46.7427C216.275 45.5404 219.068 44.9396 222.16 44.9396C225.251 44.9396 228.07 45.5404 230.614 46.7427C233.156 47.9451 235.326 49.5487 237.122 51.5522C238.917 53.5571 240.312 55.9102 241.31 58.6154C242.306 61.3205 242.806 64.1758 242.806 67.1821C242.805 70.1868 242.305 73.0421 241.31 75.7472Z" fill="currentColor" />
                     <path d="M438 -3H421.694V102.197H438V-3Z" fill="currentColor" />
                     <path d="M139.43 102.197H155.735V48.2834H183.712V32.1665H139.43V102.197Z" fill="currentColor" />
                     <path d="M324.49 32.1665L303.995 85.794L283.498 32.1665H266.983L293.748 102.197H314.242L341.006 32.1665H324.49Z" fill="currentColor" />
                     <path d="M376.571 30.3656C356.603 30.3656 340.797 46.8497 340.797 67.1828C340.797 89.6597 356.094 104 378.661 104C391.29 104 399.354 99.1488 409.206 88.5848L398.189 80.0226C398.183 80.031 389.874 90.9895 377.468 90.9895C363.048 90.9895 356.977 79.3111 356.977 73.269H411.075C413.917 50.1328 398.775 30.3656 376.571 30.3656ZM357.02 61.0967C357.145 59.7487 359.023 43.3761 376.442 43.3761C393.861 43.3761 395.978 59.7464 396.099 61.0967H357.02Z" fill="currentColor" />
                </svg>
            </div>
        </main>
    </div>

    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif

    <script>
        document.getElementById('analyzeForm').addEventListener('submit', async function(e) {
            e.preventDefault(); 
            
            const fileInput = document.getElementById('resume');
            const submitBtn = document.getElementById('submitBtn');
            const outputDiv = document.getElementById('output');

            if(fileInput.files.length === 0) {
                alert("Please select a file first.");
                return;
            }

            const formData = new FormData();
            formData.append('resume', fileInput.files[0]);

            const originalBtnText = submitBtn.innerText;
            submitBtn.innerText = "Analyzing...";
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75');
            
            outputDiv.classList.add('hidden');

            try {
                const response = await fetch('/api/match', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();

                document.getElementById('role').innerText = data.suggested_role || "N/A";

                const skillsDiv = document.getElementById('skill_gaps');
                skillsDiv.innerHTML = ''; 
                if(data.skill_gaps && data.skill_gaps.length > 0) {
                    data.skill_gaps.forEach(skill => {
                        const span = document.createElement('span');
                        span.className = 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-200 text-xs font-semibold px-2.5 py-0.5 rounded border border-red-200 dark:border-red-800';
                        span.innerText = skill;
                        skillsDiv.appendChild(span);
                    });
                } else {
                    skillsDiv.innerHTML = '<span class="text-sm text-green-600 font-medium">No major skill gaps found!</span>';
                }

                const tipsList = document.getElementById('learning_tips');
                tipsList.innerHTML = '';
                if(data.learning_tips && data.learning_tips.length > 0) {
                    data.learning_tips.forEach(tip => {
                        const li = document.createElement('li');
                        li.className = 'flex items-start';
                        li.innerHTML = `
                            <svg class="w-4 h-4 text-laravel mt-1 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>${tip}</span>
                        `;
                        tipsList.appendChild(li);
                    });
                } else {
                    tipsList.innerHTML = '<li class="text-gray-500 italic">No specific tips at this time.</li>';
                }

                outputDiv.classList.remove('hidden');

            } catch (error) {
                alert('Error analyzing resume: ' + error.message);
                console.error(error);
            } finally {
                submitBtn.innerText = originalBtnText;
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-75');
            }
        });
    </script>
</body>
</html>