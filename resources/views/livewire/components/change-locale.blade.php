<button
    type="button"
    class="group inline-flex text-sm px-3 py-1.5 rounded-md bg-white/10 items-center gap-2 text-gray-300 hover:text-white"
    wire:click="changeLocale"
>
    <svg viewBox="0 0 301 225" >
        <g fill="none">
          <path d="m.5 0h300v225h-300z" fill="#007fff"/>
          <path d="m14 45h31.5l9.75-31.5 9.75 31.5h31.5l-25.5 19.5 9.75 31.5-25.5-19.5-25.5 19.5 9.75-31.5zm267.75-45-281.25 168.75v56.25h18.75l281.25-168.75v-56.25z" fill="#f7d618"/>
          <path d="m300.5 0-300 180v45l300-180z" fill="#ce1021"/>
        </g>
    </svg>
    <span>{{ $this->locale }}</span>
</button>
