<div class="text-[13px] leading-[20px] flex-1 p-6 pb-12 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">
    <h1 class="text-2xl">
        @if($pausing)
            {{ __('schedule-manager::schedule-manager.pausing_command', ['command' => $pausingCommand])  }}
        @else
            {{ __('schedule-manager::schedule-manager.title')  }}
        @endif
    </h1>
    @if($pausing)
        <form wire:submit="pause">
            <div class="mt-4 flex flex-col gap-4">
                <div>
                    <h3>{{ __('schedule-manager::schedule-manager.form.description') }}</h3>
                    <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           type="text" wire:model="description"
                           placeholder="{{ __('schedule-manager::schedule-manager.form.description') }}">
                </div>
                <div>
                    <h3>{{ __('schedule-manager::schedule-manager.form.pause_until') }}</h3>
                    <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           type="datetime-local" wire:model="pauseUntil"
                           placeholder="{{ __('schedule-manager::schedule-manager.form.pause_until') }}">
                </div>
                <div>
                    <button class="inline-block dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black px-5 py-1.5 bg-[#1b1b18] rounded-sm border border-black text-white text-sm leading-normal"
                            type="submit">
                        {{ __('schedule-manager::schedule-manager.actions.pause') }}
                    </button>
                </div>
            </div>
        </form>
    @else
        <table class="w-full text-left table-auto min-w-max">
            <thead>
            <tr>
                <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">
                    {{ __('schedule-manager::schedule-manager.table.schedule') }}
                </th>
                <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">
                    {{ __('schedule-manager::schedule-manager.table.paused') }}
                </th>
                <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">
                    {{ __('schedule-manager::schedule-manager.table.actions') }}
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($schedules as $schedule)
                <tr wire:key="{{ $schedule['mutex_name'] }}">
                    <td class="p-4 border-b border-blue-gray-50">{{ $schedule['command'] }}</td>
                    <td class="p-4 border-b border-blue-gray-50">
                        {{ $schedule['is_paused'] ? ($schedule['pause_until'] ?
                            __('schedule-manager::schedule-manager.table.paused_until', ['date' => $schedule['pause_until']]) :
                            __('schedule-manager::schedule-manager.table.paused_forever')) : __('schedule-manager::schedule-manager.table.no') }}
                    </td>
                    <td class="p-4 border-b border-blue-gray-50">
                        @if($schedule['is_paused'])
                            <button class="inline-block dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black px-5 py-1.5 bg-[#1b1b18] rounded-sm border border-black text-white text-sm leading-normal"
                                    wire:click="resume('{{ $schedule['mutex_name'] }}')">
                                {{ __('schedule-manager::schedule-manager.actions.resume') }}
                            </button>
                        @else
                            <button class="inline-block dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black px-5 py-1.5 bg-[#1b1b18] rounded-sm border border-black text-white text-sm leading-normal"
                                    wire:click="startPausing('{{ $schedule['mutex_name'] }}')">
                                {{ __('schedule-manager::schedule-manager.actions.pause') }}
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
