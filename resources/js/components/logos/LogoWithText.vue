<template>
  <svg viewBox="0 0 280 100" fill="none" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <filter id="textShadow" x="-20%" y="-20%" width="150%" height="150%">
        <feOffset dx="3" dy="2" result="offsetblur" />
        <feGaussianBlur in="offsetblur" stdDeviation="1.5" result="blur" />
        <feComponentTransfer>
          <feFuncA type="linear" slope="0.4" />
        </feComponentTransfer>
        <feMerge>
          <feMergeNode />
          <feMergeNode in="SourceGraphic" />
        </feMerge>
      </filter>
      <linearGradient
        id="lineGradientText"
        x1="25"
        y1="35"
        x2="75"
        y2="35"
        gradientUnits="userSpaceOnUse"
      >
        <stop offset="0%" stop-color="currentColor" stop-opacity="0.8" />
        <stop offset="50%" stop-color="currentColor" />
        <stop offset="100%" stop-color="currentColor" stop-opacity="0.9" />
      </linearGradient>
    </defs>
    <g transform="translate(0, 0)">
      <circle
        cx="50"
        cy="50"
        r="48"
        stroke="currentColor"
        stroke-width="1.5"
        class="text-slate-200 dark:text-slate-800"
      />
      <g filter="url(#textShadow)" class="text-amber-600 dark:text-amber-500">
        <rect
          x="25"
          y="36"
          width="50"
          height="5"
          rx="2.5"
          fill="url(#lineGradientText)"
        />
        <rect
          x="25"
          y="49"
          width="50"
          height="5"
          rx="2.5"
          fill="url(#lineGradientText)"
        />
        <rect
          x="25"
          y="62"
          width="50"
          height="5"
          rx="2.5"
          fill="url(#lineGradientText)"
        />
      </g>
      <circle
        cx="35"
        cy="20"
        r="5"
        fill="currentColor"
        class="text-amber-500"
      />
    </g>
    <text
      x="110"
      y="62"
      class="fill-slate-900 font-serif dark:fill-white"
      style="font-size: 42px; font-weight: 700; letter-spacing: -0.02em"
    >
      I Ching
    </text>
    <text
      x="112"
      y="82"
      class="fill-slate-400 font-sans"
      style="
        font-size: 14px;
        font-weight: 500;
        letter-spacing: 0.2em;
        text-transform: uppercase;
      "
    >
      Cabinet
    </text>
  </svg>
</template>
