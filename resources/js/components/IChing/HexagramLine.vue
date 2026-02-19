<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    value: number;
}

const props = defineProps<Props>();

const isYang = computed(() => [7, 9].includes(props.value));
const isChanging = computed(() => [6, 8].includes(props.value));
</script>

<template>
    <div class="relative flex h-10 w-full items-center justify-center px-4">
        <div
            v-if="isYang"
            data-test="solid-line"
            class="h-3 w-full rounded-sm shadow-sm transition-colors duration-500"
            :class="isChanging ? 'bg-amber-400' : 'bg-slate-200'"
        ></div>

        <div v-else data-test="broken-line" class="flex w-full justify-between">
            <div
                class="h-3 w-[45%] rounded-sm shadow-sm transition-colors duration-500"
                :class="isChanging ? 'bg-amber-400' : 'bg-slate-200'"
            ></div>
            <div
                class="h-3 w-[45%] rounded-sm shadow-sm transition-colors duration-500"
                :class="isChanging ? 'bg-amber-400' : 'bg-slate-200'"
            ></div>
        </div>

        <div
            v-if="isChanging"
            data-test="changing-marker"
            class="absolute -right-2 flex h-3 w-3"
        >
            <span
                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-amber-400 opacity-75"
            ></span>
            <span
                class="relative inline-flex h-3 w-3 rounded-full bg-amber-600 shadow-sm"
            ></span>
        </div>
    </div>
</template>
