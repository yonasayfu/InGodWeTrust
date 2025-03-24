<script setup lang="ts">
import { useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Plus, X, User, Mail, Phone, MapPin, Calendar, Banknote, Image, ArrowLeft } from 'lucide-vue-next';
import { useToast } from '@/composables/useToast';

// Define proper types for form data
type SupporterFormData = {
  name: string;
  age: string | number;
  gender: string;
  address: string;
  phone_number: string;
  email: string;
  support_type: string;
  contribution_amount: string | number;
  commit_duration: string | number;
  start_date: string;
  bank_details: string;
  testimonial_content: string;
  photo: File | null;
};

// Add toast functionality
const { toast } = useToast();

// Initialize form with default values
const form = useForm<SupporterFormData>({
  name: '',
  age: '',
  gender: '',
  address: '',
  phone_number: '',
  email: '',
  support_type: '',
  contribution_amount: '',
  commit_duration: '12', // Default value to prevent null constraint violation
  start_date: '',
  bank_details: '',
  testimonial_content: '',
  photo: null,
});

// Image preview state
const previewImage = ref<string | null>(null);

// Handle file upload with proper typing
const handleFileUpload = (e: Event) => {
  const target = e.target as HTMLInputElement;
  if (target.files?.[0]) {
    form.photo = target.files[0];
    previewImage.value = URL.createObjectURL(target.files[0]);
  }
};

// Form submission handler
const submit = () => {
  form.post(route('supporters.store'), {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      toast.success('Supporter created successfully!');
      router.visit(route('supporters.index'));
    },
    onError: (errors) => {
      toast.error('Failed to create supporter.');
      console.error('Form submission errors:', errors);
    }
  });
};

// Breadcrumb navigation
const breadcrumbs = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Supporters', href: route('supporters.index') },
  { title: 'Create Supporter', href: route('supporters.create') },
];
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 md:p-6 max-w-full">
      <!-- Header with gradient background -->
      <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-lg shadow-lg p-6 mb-6">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-2xl md:text-3xl font-bold text-white">Create New Supporter</h1>
            <p class="text-blue-100 mt-1">Add a new supporter to your organization</p>
          </div>
          <a :href="route('supporters.index')" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all">
            <ArrowLeft class="w-5 h-5" />
            <span>Back to List</span>
          </a>
        </div>
      </div>

      <!-- Form with landscape-optimized layout -->
      <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-6">
        <!-- Main content in landscape layout -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
          <!-- Left column - Personal & Contact -->
          <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-xl shadow-sm p-5 space-y-4">
              <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2 border-b pb-2">
                <User class="w-5 h-5 text-blue-500" />
                Personal Information
              </h2>
              
              <!-- Name field -->
              <div>
                <label class="block text-sm font-medium text-blue-600 mb-2">Full Name <span class="text-red-500">*</span></label>
                <div class="relative">
                  <User class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                  <input
                    v-model="form.name"
                    type="text"
                    required
                    placeholder="Enter supporter's full name"
                    class="pl-10 w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-500 shadow-sm"
                  />
                  <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                </div>
              </div>

              <!-- Age field -->
              <div>
                <label class="block text-sm font-medium text-blue-600 mb-2">Age <span class="text-red-500">*</span></label>
                <input
                  v-model="form.age"
                  type="number"
                  required
                  min="18"
                  placeholder="Enter age (18+)"
                  class="w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-500 shadow-sm"
                />
                <p v-if="form.errors.age" class="mt-1 text-sm text-red-600">{{ form.errors.age }}</p>
              </div>

              <!-- Gender field -->
              <div>
                <label class="block text-sm font-medium text-blue-600 mb-2">Gender <span class="text-red-500">*</span></label>
                <select
                  v-model="form.gender"
                  required
                  class="w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-500 shadow-sm"
                >
                  <option value="" disabled>Select Gender</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                </select>
                <p v-if="form.errors.gender" class="mt-1 text-sm text-red-600">{{ form.errors.gender }}</p>
              </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white rounded-xl shadow-sm p-5 space-y-4">
              <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2 border-b pb-2">
                <Mail class="w-5 h-5 text-blue-500" />
                Contact Information
              </h2>
              
              <!-- Email field -->
              <div>
                <label class="block text-sm font-medium text-blue-600 mb-2">Email Address <span class="text-red-500">*</span></label>
                <div class="relative">
                  <Mail class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                  <input
                    v-model="form.email"
                    type="email"
                    required
                    placeholder="example@email.com"
                    class="pl-10 w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-500 shadow-sm"
                  />
                  <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                </div>
              </div>

              <!-- Phone field -->
              <div>
                <label class="block text-sm font-medium text-blue-600 mb-2">Phone Number <span class="text-red-500">*</span></label>
                <div class="relative">
                  <Phone class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                  <input
                    v-model="form.phone_number"
                    type="tel"
                    required
                    placeholder="+1 (123) 456-7890"
                    class="pl-10 w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-500 shadow-sm"
                  />
                  <p v-if="form.errors.phone_number" class="mt-1 text-sm text-red-600">{{ form.errors.phone_number }}</p>
                </div>
              </div>

              <!-- Address field -->
              <div>
                <label class="block text-sm font-medium text-blue-600 mb-2">Address <span class="text-red-500">*</span></label>
                <div class="relative">
                  <MapPin class="absolute left-3 top-3 w-5 h-5 text-gray-400" />
                  <textarea
                    v-model="form.address"
                    required
                    rows="3"
                    placeholder="Enter full address including city and postal code"
                    class="pl-10 w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-500 shadow-sm"
                  ></textarea>
                  <p v-if="form.errors.address" class="mt-1 text-sm text-red-600">{{ form.errors.address }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Middle column - Support Details & Photo -->
          <div class="lg:col-span-1 space-y-6">
            <!-- Support Details -->
            <div class="bg-white rounded-xl shadow-sm p-5 space-y-4">
              <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2 border-b pb-2">
                <Banknote class="w-5 h-5 text-blue-500" />
                Support Details
              </h2>
              
              <!-- Support Type field -->
              <div>
                <label class="block text-sm font-medium text-blue-600 mb-2">Support Type <span class="text-red-500">*</span></label>
                <select
                  v-model="form.support_type"
                  required
                  class="w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-500 shadow-sm"
                >
                  <option value="" disabled>Select Type</option>
                  <option value="monthly">Monthly</option>
                  <option value="one-time">One-Time</option>
                  <option value="yearly">Yearly</option>
                </select>
                <p v-if="form.errors.support_type" class="mt-1 text-sm text-red-600">{{ form.errors.support_type }}</p>
              </div>

              <!-- Contribution Amount field -->
              <div>
                <label class="block text-sm font-medium text-blue-600 mb-2">Contribution Amount <span class="text-red-500">*</span></label>
                <div class="relative">
                  <Banknote class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                  <input
                    v-model="form.contribution_amount"
                    type="number"
                    step="0.01"
                    required
                    placeholder="0.00"
                    class="pl-10 w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-500 shadow-sm"
                  />
                  <p v-if="form.errors.contribution_amount" class="mt-1 text-sm text-red-600">{{ form.errors.contribution_amount }}</p>
                </div>
              </div>

              <!-- Commitment Period field -->
              <div>
                <label class="block text-sm font-medium text-blue-600 mb-2">Commitment Period <span class="text-red-500">*</span></label>
                <input
                  v-model="form.commit_duration"
                  type="number"
                  min="1"
                  required
                  placeholder="Number of months"
                  class="w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-500 shadow-sm"
                />
                <p class="text-xs text-gray-500 mt-1">Enter the number of months for the commitment</p>
                <p v-if="form.errors.commit_duration" class="mt-1 text-sm text-red-600">{{ form.errors.commit_duration }}</p>
              </div>

              <!-- Start Date field -->
              <div>
                <label class="block text-sm font-medium text-blue-600 mb-2">Start Date <span class="text-red-500">*</span></label>
                <div class="relative">
                  <Calendar class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                  <input
                    v-model="form.start_date"
                    type="date"
                    required
                    class="pl-10 w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-500 shadow-sm"
                  />
                  <p v-if="form.errors.start_date" class="mt-1 text-sm text-red-600">{{ form.errors.start_date }}</p>
                </div>
              </div>
            </div>

            <!-- Photo Upload -->
            <div class="bg-white rounded-xl shadow-sm p-5 space-y-4">
              <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2 border-b pb-2">
                <Image class="w-5 h-5 text-blue-500" />
                Profile Photo
              </h2>
              
              <div class="flex flex-col items-center gap-4">
                <!-- Image preview -->
                <div v-if="previewImage" class="relative group w-full">
                  <img 
                    :src="previewImage" 
                    class="w-full h-48 object-cover rounded-lg border-2 border-blue-200 shadow-md transition-all"
                    alt="Profile preview"
                  />
                  <button
                    type="button"
                    @click="previewImage = null; form.photo = null"
                    class="absolute -top-2 -right-2 bg-red-500 text-white p-1.5 rounded-full hover:bg-red-600 shadow-lg transition-all"
                    aria-label="Remove photo"
                  >
                    <X class="w-4 h-4" />
                  </button>
                </div>
                
                <!-- Upload area -->
                <label v-if="!previewImage" class="cursor-pointer w-full">
                  <input 
                    type="file" 
                    @change="handleFileUpload" 
                    class="hidden" 
                    accept="image/png, image/jpeg"
                  />
                  <div class="w-full h-48 flex flex-col items-center justify-center rounded-lg border-2 border-dashed border-blue-300 hover:border-blue-500 hover:bg-blue-50 transition-all">
                    <Image class="w-12 h-12 text-blue-400 mb-2" />
                    <span class="text-sm font-medium text-blue-600">Click to upload photo</span>
                    <span class="text-xs text-gray-500 mt-1">JPG or PNG, max 2MB</span>
                  </div>
                </label>
              </div>
              <p v-if="form.errors.photo" class="mt-1 text-sm text-red-600">{{ form.errors.photo }}</p>
            </div>
          </div>

          <!-- Right column - Additional Info -->
          <div class="lg:col-span-1 space-y-6">
            <!-- Bank Details -->
            <div class="bg-white rounded-xl shadow-sm p-5 space-y-4">
              <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2 border-b pb-2">
                <Banknote class="w-5 h-5 text-blue-500" />
                Bank Details
              </h2>
              <textarea
                v-model="form.bank_details"
                required
                rows="5"
                class="w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-500 shadow-sm"
                placeholder="Account holder name: 
Bank name: 
Account number: 
Routing number: 
Branch:"
              ></textarea>
              <p v-if="form.errors.bank_details" class="mt-1 text-sm text-red-600">{{ form.errors.bank_details }}</p>
            </div>

            <!-- Testimonial -->
            <div class="bg-white rounded-xl shadow-sm p-5 space-y-4">
              <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2 border-b pb-2">
                <User class="w-5 h-5 text-blue-500" />
                Testimonial
              </h2>
              <textarea
                v-model="form.testimonial_content"
                rows="4"
                class="w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-500 shadow-sm"
                placeholder="Enter any testimonial or feedback from the supporter that can be used for promotional purposes..."
              ></textarea>
              <p v-if="form.errors.testimonial_content" class="mt-1 text-sm text-red-600">{{ form.errors.testimonial_content }}</p>
            </div>
          </div>
        </div>

        <!-- Submit Button Section -->
        <div class="mt-8">
          <div class="bg-white rounded-xl shadow-sm p-5">
            <div class="flex justify-between items-center">
              <p class="text-sm text-gray-500">
                <span class="text-red-500">*</span> Required fields
              </p>
              <Button
                type="submit"
                :disabled="form.processing"
                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2"
                :class="{ 'opacity-70 cursor-not-allowed': form.processing }"
              >
                <span v-if="form.processing" class="inline-block w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                <Plus v-else class="w-5 h-5" />
                <span>{{ form.processing ? 'Creating Supporter...' : 'Create Supporter' }}</span>
              </Button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<style scoped>
/* Base transitions */
.rounded-xl {
  transition: transform 0.2s, box-shadow 0.2s;
}

.rounded-xl:hover {
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Form element transitions */
input, select, textarea {
  transition: border-color 0.2s, box-shadow 0.2s;
}

/* Accessibility focus states */
input:focus, select:focus, textarea:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
}

/* Placeholder styling */
::placeholder {
  color: #9ca3af;
  opacity: 0.7;
}

/* Hover effects */
input:hover, select:hover, textarea:hover {
  border-color: #93c5fd;
}

/* Card animations */
.bg-white {
  transform: translateY(0);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* Button animations */
button {
  transition: all 0.2s ease;
}

button:hover:not(:disabled) {
  transform: translateY(-2px);
}

button:active:not(:disabled) {
  transform: translateY(0);
}

/* Required field indicators */
.text-red-500 {
  font-weight: bold;
}

/* Error message animations */
[v-if="form.errors"] {
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Screen reader utilities */
.sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border-width: 0;
}

/* Upload area hover effect */
.border-dashed:hover {
  transform: scale(1.02);
}
</style>