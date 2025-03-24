<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Edit, ArrowLeft, Printer } from 'lucide-vue-next';

// Define proper type for supporter prop
type Supporter = {
  id: number;
  name: string;
  age: number;
  gender: string;
  address: string;
  phone_number: string;
  email: string;
  support_type: string;
  contribution_amount: number;
  commit_duration: number;
  start_date: string;
  bank_details: string;
  testimonial_content?: string;
  photo_url?: string;
};

defineProps<{
  supporter: Supporter;
}>();

const handlePrint = (id: number) => {
  window.open(route('supporters.print-show', id), '_blank');
};

const breadcrumbs = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Supporters', href: '/dashboard/supporters' },
  { title: 'View Details', href: null },
];
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <!-- Header section with navigation and action buttons -->
      <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
          <Link :href="route('supporters.index')" class="text-gray-600 hover:text-gray-900">
            <ArrowLeft class="w-5 h-5" />
          </Link>
          <h1 class="text-3xl font-bold">Supporter Details</h1>
        </div>
        <div class="flex items-center gap-3">
          <Button variant="outline" class="flex items-center gap-2" @click="handlePrint(supporter.id)">
            <Printer class="w-4 h-4" />
            <span>Print</span>
          </Button>
          <Link :href="route('supporters.edit', supporter.id)">
            <Button variant="primary" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700">
              <Edit class="w-4 h-4" />
              <span>Edit</span>
            </Button>
          </Link>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Profile header with photo or initial -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 border-b">
          <div class="flex items-center gap-6">
            <div v-if="supporter.photo_url" class="h-24 w-24 rounded-full overflow-hidden border-4 border-white shadow-md">
              <img :src="supporter.photo_url" :alt="supporter.name" class="h-full w-full object-cover" />
            </div>
            <div v-else class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-2xl font-bold border-4 border-white shadow-md">
              {{ supporter.name.charAt(0) }}
            </div>
            <div>
              <h2 class="text-2xl font-bold text-gray-800">{{ supporter.name }}</h2>
              <div class="mt-1 flex items-center gap-3">
                <span class="px-3 py-1 inline-flex text-sm font-medium rounded-full bg-green-100 text-green-800">
                  {{ supporter.support_type }}
                </span>
                <span class="text-gray-500">Since {{ new Date(supporter.start_date).toLocaleDateString() }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Information cards section -->
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Personal Information -->
          <div class="bg-white rounded-lg border p-5 hover:shadow-md transition-shadow">
            <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Personal Information</h3>
            <div class="space-y-3">
              <div class="flex justify-between">
                <span class="text-gray-500">Age:</span>
                <span class="font-medium">{{ supporter.age }} years</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Gender:</span>
                <span class="font-medium">{{ supporter.gender }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Email:</span>
                <span class="font-medium">{{ supporter.email }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Phone:</span>
                <span class="font-medium">{{ supporter.phone_number }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Address:</span>
                <span class="font-medium">{{ supporter.address }}</span>
              </div>
            </div>
          </div>

          <!-- Support Details -->
          <div class="bg-white rounded-lg border p-5 hover:shadow-md transition-shadow">
            <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Support Details</h3>
            <div class="space-y-3">
              <div class="flex justify-between">
                <span class="text-gray-500">Support Type:</span>
                <span class="font-medium">{{ supporter.support_type }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Contribution:</span>
                <span class="font-medium text-green-600">${{ supporter.contribution_amount.toLocaleString() }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Commitment Duration:</span>
                <span class="font-medium">{{ supporter.commit_duration }} months</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Start Date:</span>
                <span class="font-medium">{{ new Date(supporter.start_date).toLocaleDateString() }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Bank Details:</span>
                <span class="font-medium">{{ supporter.bank_details }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Testimonial section (conditional) -->
        <div v-if="supporter.testimonial_content" class="p-6 border-t">
          <h3 class="text-lg font-semibold text-gray-700 mb-4">Testimonial</h3>
          <div class="bg-gray-50 p-4 rounded-lg italic text-gray-700 border-l-4 border-blue-500">
            "{{ supporter.testimonial_content }}"
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
.transition-shadow {
  transition: box-shadow 0.3s ease;
}
</style>