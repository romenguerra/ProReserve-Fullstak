<script setup>
import { computed } from 'vue';
import { format, startOfMonth, endOfMonth, eachDayOfInterval, isSameMonth, isToday, startOfWeek, endOfWeek, isSameDay } from "date-fns";

const props = defineProps({
  currentDate: { type: Date, required: true },
  bookings: { type: Array, required: true },
  selectedDate: { type: Date, default: null }
});

const emit = defineEmits(['dateClick']);

const DAYS_OF_WEEK = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

const weeks = computed(() => {
  const monthStart = startOfMonth(props.currentDate);
  const monthEnd = endOfMonth(props.currentDate);
  const calendarStart = startOfWeek(monthStart);
  const calendarEnd = endOfWeek(monthEnd);
  
  const calendarDays = eachDayOfInterval({ start: calendarStart, end: calendarEnd });
  
  const w = [];
  for (let i = 0; i < calendarDays.length; i += 7) {
    w.push(calendarDays.slice(i, i + 7));
  }
  return w;
});

const getBookingsForDate = (date) => {
  return props.bookings.filter(booking => {
    const bookingDate = new Date(booking.date);
    return isSameDay(bookingDate, date) && booking.status === "booked";
  });
};
</script>

<template>
  <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
    <!-- Days of Week Header -->
    <div class="grid grid-cols-7 border-b border-gray-200 bg-gray-50/50">
      <div v-for="day in DAYS_OF_WEEK" :key="day" class="text-center py-4 text-sm font-semibold text-gray-500 border-r border-gray-200 last:border-r-0">
        {{ day }}
      </div>
    </div>

    <!-- Calendar Grid -->
    <div class="grid grid-cols-7">
      <div v-for="(week, weekIndex) in weeks" :key="weekIndex" class="contents">
        <template v-for="day in week" :key="day.toISOString()">
          <div
            @click="emit('dateClick', day)"
            :class="[
              'min-h-[140px] p-3 border-r border-b border-gray-200 last:border-r-0 cursor-pointer transition-colors hover:bg-gray-50/70',
              !isSameMonth(day, currentDate) ? 'bg-gray-50/30' : 'bg-white',
              selectedDate && isSameDay(day, selectedDate) ? 'bg-blue-50 ring-2 ring-blue-500 ring-inset hover:bg-blue-50' : ''
            ]"
          >
            <div class="flex items-center justify-between mb-2">
              <span
                :class="[
                  'text-sm font-medium w-8 h-8 flex items-center justify-center rounded-full',
                  isToday(day) ? 'bg-blue-600 text-white' : '',
                  !isSameMonth(day, currentDate) && !isToday(day) ? 'text-gray-400' : (!isToday(day) ? 'text-gray-900' : '')
                ]"
              >
                {{ format(day, "d") }}
              </span>
              <span v-if="getBookingsForDate(day).length > 0" class="text-xs font-semibold text-blue-600 bg-blue-100 px-2 py-0.5 rounded-full">
                {{ getBookingsForDate(day).length }}
              </span>
            </div>

            <!-- Bookings for this day -->
            <div class="space-y-1">
              <div
                v-for="booking in getBookingsForDate(day).slice(0, 3)"
                :key="booking.id"
                class="text-xs px-2 py-1.5 rounded bg-blue-100 border border-blue-200 truncate hover:bg-blue-200 transition-colors"
                @click.stop
              >
                <div class="font-medium text-blue-900">{{ booking.startTime }}</div>
                <div class="text-blue-700 truncate">{{ booking.customerName }}</div>
              </div>
              <div v-if="getBookingsForDate(day).length > 3" class="text-xs text-gray-500 px-2 font-medium">
                +{{ getBookingsForDate(day).length - 3 }} more
              </div>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>
