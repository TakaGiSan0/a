<footer class="w-full bg-neutral text-neutral-content py-10 bg-gray-100 dark:bg-gray-800 dark:text-[#f1f1f1]">
    <div class="w-full p-4 py-6 lg:py-8">
        <div class="flex items-start space-x-6 mb-10">
            <!-- Legend Product Code -->
            <div class="flex items-center">
                <p class="text-xs  text-gray-500 uppercase dark:text-white">
                    Legend Product Code :
                </p>
            </div>

            <!-- Resources Section -->
            <div class="grid grid-cols-2 gap-6 sm:gap-8 sm:grid-cols-6 text-xs ">
                <div class="dark:text-white">
                    <ul class="text-gray-500 dark:text-white ">
                        <li>
                            <p class="text-xs   text-gray-500 uppercase dark:text-white">
                                NA : NA
                            </p>

                        </li>
                        <li class="">
                            <p>NA : NA</p>
                        </li>
                        <li>
                            <p>NA : NA</p>
                        </li>
                    </ul>
                </div>
                
            </div>
        </div>
        <div class="flex items-start space-x-6 mb-10">
            <div class="flex items-center">
                <p class="text-sm  text-gray-500 uppercase dark:text-white">
                    Legend Skill Code :
                </p>
            </div>

      
            @php
                $chunks = $legendSkills->chunk(5);
            @endphp

            <div
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 text-xs text-gray-500 dark:text-white">
                @foreach ($chunks as $chunk)
                    <div>
                        <ul>
                            @foreach ($chunk as $skill)
                                <li>
                                    <p class="text-xs text-gray-500 uppercase dark:text-white">
                                        {{ $skill->skill_code }} : {{ $skill->job_skill }}
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>

        </div>
        <div class="flex items-start space-x-6">
            <!-- Legend Product Code -->
            <div class="flex items-center pr-10">
                <p class="text-sm  text-gray-500 uppercase dark:text-white">
                    Skill Level:
                </p>
            </div>

            <!-- Resources Section -->
            <div class="grid grid-cols-2 gap-6 sm:gap-8 sm:grid-cols-6 text-xs">
                <div>
                    <ul class="text-gray-500 dark:text-white">
                        <li>
                            <p class="text-xs   text-gray-500 uppercase dark:text-white">
                                1 = LEVEL 1 (work under - )
                            </p>

                        </li>
                        <li class="">
                            <p>2 = LEVEL 2 (work according -)</p>
                        </li>
                        <li>
                            <p>3 = Level 3 (expert)</p>
                        </li>
                        <li>
                            <p>4 = Level 4 (expert & trainer)   
                            </p>
                        </li>

                    </ul>
                </div>

            </div>
        </div>
    </div>
</footer>