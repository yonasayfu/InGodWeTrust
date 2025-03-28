<template>
  <AppLayout>
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-5" aria-label="Breadcrumb">
          <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
              <Link :href="route('dashboard')" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                <HomeIcon class="w-4 h-4 mr-2" />
                Dashboard
              </Link>
            </li>
            <li>
              <div class="flex items-center">
                <ChevronRightIcon class="w-5 h-5 text-gray-400" />
                <Link :href="route('target-donations.index')" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                  Target Donations
                </Link>
              </div>
            </li>
            <li aria-current="page">
              <div class="flex items-center">
                <ChevronRightIcon class="w-5 h-5 text-gray-400" />
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Create</span>
              </div>
            </li>
          </ol>
        </nav>
        
        <div class="flex items-center justify-between mb-6">
          <div>
            <h1 class="text-2xl font-semibold text-gray-900">Create Target Donation</h1>
            <p class="mt-1 text-sm text-gray-600">Add a new target donation period and amount.</p>
          </div>
          <Link
            :href="route('target-donations.index')"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
          >
            Back to List
          </Link>
        </div>
        
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
          <form @submit.prevent="submit" class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="period" class="block text-sm font-medium text-gray-700">Period</label>
                <select
                  id="period"
                  v-model="form.period"
                  class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                >
                  <option value="" disabled>Choose a period</option>
                  <option value="January - March">January - March</option>
                  <option value="April - June">April - June</option>
                  <option value="July - September">July - September</option>
                  <option value="October - December">October - December</option>
                  <option value="Annual">Annual</option>
                </select>
                <div v-if="form.errors.period" class="text-red-500 text-sm mt-1">{{ form.errors.period }}</div>
              </div>
              
              <div>
                <label for="target_amount" class="block text-sm font-medium text-gray-700">Target Amount</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">$</span>
                  </div>
                  <input
                    id="target_amount"
                    type="number"
                    step="0.01"
                    v-model="form.target_amount"
                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                    placeholder="0.00"
                  />
                </div>
                <div v-if="form.errors.target_amount" class="text-red-500 text-sm mt-1">{{ form.errors.target_amount }}</div>
              </div>
              
              <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input
                  id="start_date"
                  type="date"
                  v-model="form.start_date"
                  class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                />
                <div v-if="form.errors.start_date" class="text-red-500 text-sm mt-1">{{ form.errors.start_date }}</div>
              </div>
              
              <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                <input
                  id="end_date"
                  type="date"
                  v-model="form.end_date"
                  class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                />
                <div v-if="form.errors.end_date" class="text-red-500 text-sm mt-1">{{ form.errors.end_date }}</div>
              </div>
            </div>
            
            <div class="flex justify-end pt-5 border-t border-gray-200">
              <Link
                :href="route('target-donations.index')"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3"
              >
                Cancel
              </Link>
              <button
                type="submit"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                :disabled="form.processing"
              >
                <span v-if="form.processing">Saving...</span>
                <span v-else>Save Target Donation</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

// No changes needed to the template

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { HomeIcon, ChevronRightIcon } from 'lucide-vue-next';

const form = useForm({
  period: '',
  target_amount: '',
  start_date: '',
  end_date: '',
});

const submit = () => {
  form.post(route('target-donations.store'));
};
</script>
  