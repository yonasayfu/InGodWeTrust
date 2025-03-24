<script setup lang="ts">
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { ArrowLeft, Save } from 'lucide-vue-next';

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

const props = defineProps<{
  supporter: Supporter;
}>();

// Create form with existing supporter data
const form = useForm({
  name: props.supporter.name,
  age: props.supporter.age,
  gender: props.supporter.gender,
  address: props.supporter.address,
  phone_number: props.supporter.phone_number,
  email: props.supporter.email,
  support_type: props.supporter.support_type,
  contribution_amount: props.supporter.contribution_amount,
  commit_duration: props.supporter.commit_duration,
  start_date: props.supporter.start_date,
  bank_details: props.supporter.bank_details,
  testimonial_content: props.supporter.testimonial_content || '',
  photo: null as File | null,
});

// Handle file selection
const photoInput = ref<HTMLInputElement | null>(null);
const photoPreview = ref<string | null>(props.supporter.photo_url || null);

const selectNewPhoto = () => {
  photoInput.value?.click();
};

const updatePhotoPreview = () => {
  const photo = photoInput.value?.files?.[0];
  if (!photo) return;
  
  form.photo = photo;
  
  const reader = new FileReader();
  reader.onload = (e) => {
    photoPreview.value = e.target?.result as string;
  };
  reader.readAsDataURL(photo);
};

// Submit form
const submit = () => {
  form.post(route('supporters.update', props.supporter.id), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
    },
  });
};

const breadcrumbs = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Supporters', href: '/dashboard/supporters' },
  { title: 'Edit Supporter', href: null },
];
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <!-- Header section with navigation and title -->
      <div class="flex items-center gap-4 mb-6">
        <Link :href="route('supporters.index')" class="text-gray-600 hover:text-gray-900">
          <ArrowLeft class="w-5 h-5" />
        </Link>
        <h1 class="text-3xl font-bold">Edit Supporter</h1>
      </div>

      <div class="bg-white rounded-lg shadow overflow-hidden">
        <form @submit.prevent="submit">
          <!-- Form content -->
          <div class="p-6 space-y-6">
            <!-- Photo upload section -->
            <div class="flex flex-col items-center space-y-4">
              <div v-if="photoPreview" class="h-32 w-32 rounded-full overflow-hidden border-4 border-white shadow-md">
                <img :src="photoPreview" alt="Preview" class="h-full w-full object-cover" />
              </div>
              <div v-else class="h-32 w-32 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-3xl font-bold border-4 border-white shadow-md">
                {{ form.name.charAt(0) }}
              </div>
              
              <div>
                <input
                  ref="photoInput"
                  type="file"
                  class="hidden"
                  @change="updatePhotoPreview"
                  accept="image/*"
                />
                <Button 
                  type="button" 
                  variant="outline" 
                  @click="selectNewPhoto"
                  class="mt-2"
                >
                  Change Photo
                </Button>
              </div>
            </div>

            <!-- Form fields in a two-column grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Personal Information -->
              <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Personal Information</h3>
                
                <div class="space-y-4">
                  <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input
                      id="name"
                      v-model="form.name"
                      type="text"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      required
                    />
                    <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
                  </div>
                  
                  <div>
                    <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                    <input
                      id="age"
                      v-model="form.age"
                      type="number"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      required
                    />
                    <div v-if="form.errors.age" class="text-red-500 text-sm mt-1">{{ form.errors.age }}</div>
                  </div>
                  
                  <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                    <select
                      id="gender"
                      v-model="form.gender"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      required
                    >
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                      <option value="Other">Other</option>
                    </select>
                    <div v-if="form.errors.gender" class="text-red-500 text-sm mt-1">{{ form.errors.gender }}</div>
                  </div>
                  
                  <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                      id="email"
                      v-model="form.email"
                      type="email"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      required
                    />
                    <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</div>
                  </div>
                  
                  <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input
                      id="phone_number"
                      v-model="form.phone_number"
                      type="text"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      required
                    />
                    <div v-if="form.errors.phone_number" class="text-red-500 text-sm mt-1">{{ form.errors.phone_number }}</div>
                  </div>
                  
                  <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea
                      id="address"
                      v-model="form.address"
                      rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      required
                    ></textarea>
                    <div v-if="form.errors.address" class="text-red-500 text-sm mt-1">{{ form.errors.address }}</div>
                  </div>
                </div>
              </div>
              
              <!-- Support Information -->
              <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Support Information</h3>
                
                <div class="space-y-4">
                  <div>
                    <label for="support_type" class="block text-sm font-medium text-gray-700">Support Type</label>
                    <select
                      id="support_type"
                      v-model="form.support_type"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      required
                    >
                      <option value="monthly">Monthly</option>
                      <option value="one-time">One-time</option>
                      <option value="recurring">Recurring</option>
                      <option value="annual">Annual</option>
                    </select>
                    <div v-if="form.errors.support_type" class="text-red-500 text-sm mt-1">{{ form.errors.support_type }}</div>
                  </div>
                  
                  <div>
                    <label for="contribution_amount" class="block text-sm font-medium text-gray-700">Contribution Amount</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">$</span>
                      </div>
                      <input
                        id="contribution_amount"
                        v-model="form.contribution_amount"
                        type="number"
                        step="0.01"
                        class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required
                      />
                    </div>
                    <div v-if="form.errors.contribution_amount" class="text-red-500 text-sm mt-1">{{ form.errors.contribution_amount }}</div>
                  </div>
                  
                  <div>
                    <label for="commit_duration" class="block text-sm font-medium text-gray-700">Commitment Duration (months)</label>
                    <input
                      id="commit_duration"
                      v-model="form.commit_duration"
                      type="number"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      required
                    />
                    <div v-if="form.errors.commit_duration" class="text-red-500 text-sm mt-1">{{ form.errors.commit_duration }}</div>
                  </div>
                  
                  <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input
                      id="start_date"
                      v-model="form.start_date"
                      type="date"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      required
                    />
                    <div v-if="form.errors.start_date" class="text-red-500 text-sm mt-1">{{ form.errors.start_date }}</div>
                  </div>
                  
                  <div>
                    <label for="bank_details" class="block text-sm font-medium text-gray-700">Bank Details</label>
                    <textarea
                      id="bank_details"
                      v-model="form.bank_details"
                      rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      required
                    ></textarea>
                    <div v-if="form.errors.bank_details" class="text-red-500 text-sm mt-1">{{ form.errors.bank_details }}</div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Testimonial section -->
            <div>
              <label for="testimonial_content" class="block text-sm font-medium text-gray-700">Testimonial (Optional)</label>
              <textarea
                id="testimonial_content"
                v-model="form.testimonial_content"
                rows="4"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              ></textarea>
              <div v-if="form.errors.testimonial_content" class="text-red-500 text-sm mt-1">{{ form.errors.testimonial_content }}</div>
            </div>
          </div>
          
          <!-- Form actions -->
          <div class="px-6 py-4 bg-gray-50 flex justify-end">
            <div class="flex gap-3">
              <Link :href="route('supporters.index')">
                <Button type="button" variant="outline">Cancel</Button>
              </Link>
              <Button 
                type="submit" 
                variant="primary"
                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700"
                :disabled="form.processing"
              >
                <Save class="w-4 h-4" />
                <span>Save Changes</span>
              </Button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
