<script setup>
import { computed } from 'vue';
import { format, isSameDay } from "date-fns";
import { Clock, User, Mail, Phone, Trash2, Ban, Plus } from "lucide-vue-next";

const props = defineProps({
  selectedDate: { type: Date, default: null },
  bookings: { type: Array, required: true }
});

const emit = defineEmits(['deleteBooking', 'cancelBooking', 'timeSlotClick']);

// Generate time slots from 9 AM to 5 PM
const TIME_SLOTS = [
  "09:00", "09:30", "10:00", "10:30", "11:00", "11:30",
  "12:00", "12:30", "13:00", "13:30", "14:00", "14:30",
  "15:00", "15:30", "16:00", "16:30", "17:00"
];

const filteredBookings = computed(() => {
  return props.selectedDate
    ? props.bookings.filter((booking) => {
        const bookingDate = new Date(booking.date);
        return isSameDay(bookingDate, props.selectedDate);
      })
    : [];
});

const bookedCount = computed(() => filteredBookings.value.filter(b => b.status === "booked").length);
const availableCount = computed(() => TIME_SLOTS.length - bookedCount.value);

const isTimeSlotBooked = (timeSlot) => {
  return filteredBookings.value.find((booking) => {
    if (booking.status === "cancelled") return false;
    
    // We expect time as HH:mm and duration in minutes
    const bookingStart = booking.startTime;
    const bookingEndMinutes = parseInt(bookingStart.split(":")[0]) * 60 + 
                               parseInt(bookingStart.split(":")[1]) + 
                               booking.duration;
    const slotMinutes = parseInt(timeSlot.split(":")[0]) * 60 + 
                       parseInt(timeSlot.split(":")[1]);
    const bookingStartMinutes = parseInt(bookingStart.split(":")[0]) * 60 + 
                                 parseInt(bookingStart.split(":")[1]);
    
    return slotMinutes >= bookingStartMinutes && slotMinutes < bookingEndMinutes;
  });
};
</script>

<template>
  <div v-if="!selectedDate" class="bg-white rounded-lg shadow-sm border border-gray-200 h-full">
    <div class="flex items-center justify-center h-full p-6">
      <div class="text-center text-gray-500">
        <Clock class="w-12 h-12 mx-auto mb-3 opacity-50" />
        <p>Select a date to view schedule</p>
      </div>
    </div>
  </div>

  <div v-else class="bg-white rounded-lg shadow-sm border border-gray-200 h-full flex flex-col">
    <div class="p-6 border-b border-gray-200">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-lg font-semibold leading-none tracking-tight">{{ format(selectedDate, "EEEE, MMMM d") }}</h3>
          <p class="text-sm text-gray-500 mt-1">
            {{ bookedCount }} booked &middot; {{ availableCount }} available
          </p>
        </div>
        <div class="text-right">
          <div class="text-2xl font-bold">{{ bookedCount }}</div>
          <div class="text-xs text-gray-500">Appointments</div>
        </div>
      </div>
    </div>
    
    <div class="flex-1 overflow-auto max-h-[calc(100vh-280px)]">
      <div class="p-6 space-y-2">
        <template v-for="timeSlot in TIME_SLOTS" :key="timeSlot">
          <div v-if="!isTimeSlotBooked(timeSlot)">
            <button
              @click="emit('timeSlotClick', timeSlot)"
              class="w-full p-4 border-2 border-dashed border-gray-200 rounded-lg hover:border-blue-400 hover:bg-blue-50 transition-all text-left group"
            >
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="text-sm font-medium text-gray-500 group-hover:text-blue-600">
                    {{ timeSlot }}
                  </div>
                  <div class="text-xs text-gray-500 group-hover:text-blue-600">
                    Available
                  </div>
                </div>
                <Plus class="w-4 h-4 text-gray-400 group-hover:text-blue-600" />
              </div>
            </button>
          </div>
          
          <div v-else-if="isTimeSlotBooked(timeSlot)?.startTime === timeSlot"
            :class="[
              'p-4 border-2 rounded-lg',
              isTimeSlotBooked(timeSlot).status === 'cancelled' 
                ? 'bg-gray-50 border-gray-200 opacity-60' 
                : 'bg-gradient-to-r from-blue-50 to-blue-100/50 border-blue-300'
            ]"
          >
            <div class="flex items-start justify-between gap-2 mb-3">
              <div class="flex items-center gap-3">
                <div class="flex flex-col">
                  <span class="font-semibold text-blue-900">
                    {{ isTimeSlotBooked(timeSlot).startTime }}
                  </span>
                  <span class="text-xs text-blue-600">
                    {{ isTimeSlotBooked(timeSlot).duration }} min
                  </span>
                </div>
                <div class="h-10 w-px bg-blue-200 hidden sm:block"></div>
                <div class="flex flex-col sm:flex-row items-center gap-2">
                  <span class="inline-flex items-center rounded-full border border-gray-200 bg-white px-2.5 py-0.5 text-xs font-semibold">
                    {{ isTimeSlotBooked(timeSlot).service }}
                  </span>
                  <span v-if="isTimeSlotBooked(timeSlot).status === 'cancelled'" class="inline-flex items-center rounded-full border border-red-200 bg-red-50 text-red-700 px-2.5 py-0.5 text-xs font-semibold">
                    Cancelled
                  </span>
                </div>
              </div>
              
              <div class="flex gap-1">
                <button
                  v-if="isTimeSlotBooked(timeSlot).status === 'booked'"
                  @click="emit('cancelBooking', isTimeSlotBooked(timeSlot).id)"
                  class="p-2 rounded hover:bg-orange-100 transition"
                  title="Cancel Booking"
                >
                  <Ban class="w-4 h-4 text-orange-600" />
                </button>
                <button
                  @click="emit('deleteBooking', isTimeSlotBooked(timeSlot).id)"
                  class="p-2 rounded hover:bg-red-100 transition"
                  title="Delete Booking"
                >
                  <Trash2 class="w-4 h-4 text-red-600" />
                </button>
              </div>
            </div>

            <div class="space-y-2 bg-white/60 rounded-md p-3">
              <div class="flex items-center gap-2">
                <User class="w-4 h-4 text-blue-600" />
                <span class="text-sm font-semibold text-gray-900">{{ isTimeSlotBooked(timeSlot).customerName }}</span>
              </div>
              
              <div v-if="isTimeSlotBooked(timeSlot).email" class="flex items-center gap-2">
                <Mail class="w-4 h-4 text-gray-500" />
                <span class="text-sm text-gray-500">{{ isTimeSlotBooked(timeSlot).email }}</span>
              </div>

              <div v-if="isTimeSlotBooked(timeSlot).phone" class="flex items-center gap-2">
                <Phone class="w-4 h-4 text-gray-500" />
                <span class="text-sm text-gray-500">{{ isTimeSlotBooked(timeSlot).phone }}</span>
              </div>

              <div v-if="isTimeSlotBooked(timeSlot).notes" class="mt-2 pt-2 border-t border-gray-200/50">
                <p class="text-sm text-gray-500 italic">
                  "{{ isTimeSlotBooked(timeSlot).notes }}"
                </p>
              </div>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>
