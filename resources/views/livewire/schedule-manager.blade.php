<div class="text-[13px] leading-[20px] flex-1 p-6 pb-12 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">
    <h1 class="text-2xl">
        @if($pausing)
            Pausing ({{ $pausingCommand }})
        @else
            Schedule manager
        @endif
    </h1>
    @if($pausing)
        <form wire:submit="pause">
            <div class="mt-4 flex flex-col gap-4">
                <div>
                    <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           type="text" wire:model="description" placeholder="Description (optional)">
                </div>
                <div>
                    <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           type="datetime-local" wire:model="pauseUntil" placeholder="Pause until (optional)">
                </div>
                <div>
                    <button class="inline-block dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black px-5 py-1.5 bg-[#1b1b18] rounded-sm border border-black text-white text-sm leading-normal"
                            type="submit">
                        Pause
                    </button>
                </div>
            </div>
        </form>
    @else
        <table class="w-full text-left table-auto min-w-max">
            <thead>
            <tr>
                <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">Schedule</th>
                <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">Is Paused?</th>
                <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">Pause/resume</th>
            </tr>
            </thead>
            <tbody>
            @foreach($schedules as $schedule)
                <tr wire:key="{{ $schedule['mutex_name'] }}">
                    <td class="p-4 border-b border-blue-gray-50">{{ $schedule['command'] }}</td>
                    <td class="p-4 border-b border-blue-gray-50">
                        {{ $schedule['is_paused'] ? 'Yes, ' . ($schedule['pause_until'] ? ('until ' . $schedule['pause_until']) : 'forever') : 'No' }}
                    </td>
                    <td class="p-4 border-b border-blue-gray-50">
                        @if($schedule['is_paused'])
                            <button class="inline-block dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black px-5 py-1.5 bg-[#1b1b18] rounded-sm border border-black text-white text-sm leading-normal"
                                    wire:click="resume('{{ $schedule['mutex_name'] }}')">Resume
                            </button>
                        @else
                            <button class="inline-block dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black px-5 py-1.5 bg-[#1b1b18] rounded-sm border border-black text-white text-sm leading-normal"
                                    wire:click="startPausing('{{ $schedule['mutex_name'] }}')">Pause
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
