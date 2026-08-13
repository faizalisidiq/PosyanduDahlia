<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Antrian - Posyandu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 h-screen w-screen overflow-hidden flex flex-col font-sans">

    {{-- Header --}}
    <div class="bg-teal-700 text-white p-6 shadow-md z-10">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold tracking-wider">POSYANDU SEHAT SEJAHTERA</h1>
            <div class="text-xl font-medium" id="clock"></div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="flex-1 flex p-6 gap-6 overflow-hidden">

        {{-- Left: Current Calling (60%) --}}
        <div class="w-7/12 flex flex-col gap-6">
            <div id="called-container"
                class="bg-white rounded-3xl shadow-xl border border-gray-200 flex-1 flex flex-col items-center justify-center p-10 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-4 bg-teal-500"></div>

                <h2 class="text-4xl font-bold text-gray-500 uppercase tracking-widest mb-4">Nomor Antrian</h2>

                @if ($called)
                    <div class="text-[12rem] leading-none font-black text-teal-600 mb-8 animate-pulse">
                        {{ $called->queue_number }}
                    </div>
                    <div class="text-4xl font-semibold text-gray-800 text-center">
                        {{ $called->child->name }}
                    </div>
                    <div class="text-2xl text-gray-500 mt-2">
                        {{ $called->type == 'immunization' ? 'Imunisasi' : 'Pemantauan Tumbuh Kembang' }}
                    </div>
                @else
                    <div class="text-6xl font-bold text-gray-300">
                        MENUNGGU...
                    </div>
                @endif
            </div>
        </div>

        {{-- Right: Start Queue List (40%) --}}
        <div class="w-5/12 bg-white rounded-3xl shadow-xl border border-gray-200 p-8 flex flex-col">
            <h3 class="text-2xl font-bold text-gray-700 border-b-2 border-gray-100 pb-4 mb-4">Antrian Berikutnya</h3>

            <div id="waiting-container" class="flex-1 overflow-y-auto space-y-4 pr-2">
                @forelse($waiting as $queue)
                    <div
                        class="bg-gray-50 rounded-xl p-6 flex items-center justify-between border-l-8 {{ $loop->first ? 'border-teal-500 bg-teal-50' : 'border-gray-300' }}">
                        <div>
                            <span class="text-3xl font-bold text-gray-800 block">{{ $queue->queue_number }}</span>
                            <span class="text-gray-600 font-medium">{{ Str::limit($queue->child->name, 20) }}</span>
                        </div>
                        <div class="text-right">
                            <span
                                class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-white border border-gray-200 text-gray-500">
                                {{ $queue->type == 'immunization' ? 'Imun' : 'Gizi' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-400 py-10 italic">
                        Tidak ada antrian menunggu.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="bg-teal-900 text-white p-3 text-center">
        <marquee behavior="scroll" direction="left" class="text-lg font-medium">
            Selamat Datang di Posyandu Sehat Sejahtera. Harap menunggu nomor antrian Anda dipanggil dengan tertib.
            Jagalah kebersihan lingkungan posyandu.
        </marquee>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        function updateClock() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            document.getElementById('clock').innerText = now.toLocaleDateString('id-ID', options);
        }
        setInterval(updateClock, 1000);
        updateClock();

        // AJAX Polling
        function fetchQueueData() {
            axios.get('{{ route('queues.public.data') }}')
                .then(response => {
                    const data = response.data;
                    updateCalledSection(data.called);
                    updateWaitingSection(data.waiting);
                })
                .catch(error => console.error('Error fetching queue data:', error));
        }

        function updateCalledSection(called) {
            const container = document.getElementById('called-container');
            if (called) {
                const typeLabel = called.type === 'immunization' ? 'Imunisasi' : 'Pemantauan Tumbuh Kembang';
                container.innerHTML = `
                    <div class="absolute top-0 left-0 w-full h-4 bg-teal-500"></div>
                    <h2 class="text-4xl font-bold text-gray-500 uppercase tracking-widest mb-4">Nomor Antrian</h2>
                    <div class="text-[12rem] leading-none font-black text-teal-600 mb-8 animate-pulse">
                        ${called.queue_number}
                    </div>
                    <div class="text-4xl font-semibold text-gray-800 text-center">
                        ${called.child.name}
                    </div>
                    <div class="text-2xl text-gray-500 mt-2">
                        ${typeLabel}
                    </div>
                `;
            } else {
                container.innerHTML = `
                    <div class="absolute top-0 left-0 w-full h-4 bg-teal-500"></div>
                    <h2 class="text-4xl font-bold text-gray-500 uppercase tracking-widest mb-4">Nomor Antrian</h2>
                    <div class="text-6xl font-bold text-gray-300">
                        MENUNGGU...
                    </div>
                `;
            }
        }

        function updateWaitingSection(waiting) {
            const container = document.getElementById('waiting-container');
            if (waiting.length > 0) {
                let html = '';
                waiting.forEach((queue, index) => {
                    const typeLabel = queue.type === 'immunization' ? 'Imun' : 'Gizi';
                    const borderClass = index === 0 ? 'border-teal-500 bg-teal-50' : 'border-gray-300';
                    html += `
                        <div class="bg-gray-50 rounded-xl p-6 flex items-center justify-between border-l-8 ${borderClass}">
                            <div>
                                <span class="text-3xl font-bold text-gray-800 block">${queue.queue_number}</span>
                                <span class="text-gray-600 font-medium">${queue.child.name.substring(0, 20)}</span>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-white border border-gray-200 text-gray-500">
                                    ${typeLabel}
                                </span>
                            </div>
                        </div>
                    `;
                });
                container.innerHTML = html;
            } else {
                container.innerHTML = `
                    <div class="text-center text-gray-400 py-10 italic">
                        Tidak ada antrian menunggu.
                    </div>
                `;
            }
        }

        // Poll every 3 seconds
        setInterval(fetchQueueData, 3000);
    </script>
</body>

</html>
