<template>
    <AppLayout :breadcrumbs="breadcrumbs">
      <div class="p-6">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-3xl font-bold">Target Donations</h1>
          <div class="flex items-center gap-4">
            <div class="relative flex gap-2 items-center">
              <label for="search-field" class="text-sm font-medium text-gray-600">Search by:</label>
              <select
                id="search-field"
                v-model="searchField"
                class="border rounded-lg px-3 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500"
              >
                <option value="period">Period</option>
                <option value="target_amount">Target Amount</option>
              </select>
              <div class="relative flex-1">
                <Search class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                <input
                  v-model="search"
                  type="search"
                  placeholder="Search target donations..."
                  class="pl-10 pr-4 py-2 border rounded-lg w-full focus:ring-2 focus:ring-blue-500"
                  @input="performSearch"
                />
              </div>
            </div>
            <button 
              @click="router.get(route('target-donations.create'))"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-lg shadow-md transition-all duration-300 bg-emerald-500 hover:bg-emerald-600 text-white hover:shadow-lg"
            >
              <Plus class="w-5 h-5" />
              <span class="font-semibold">Add New Target Donation</span>
            </button>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow overflow-hidden">
          <div class="flex gap-4 p-4 border-b">
            <button 
              @click="handlePrint"
              class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors"
            >
              <Printer class="w-5 h-5" />
              <span>Print</span>
            </button>
            <button 
              @click="handleExport"
              class="flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors"
            >
              <Download class="w-5 h-5" />
              <span>Export</span>
            </button>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="bg-gray-50 border-b">
                  <th
                    @click="sort('period')"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                  >
                    <div class="flex items-center gap-2">
                      <span>Period</span>
                      <div class="flex flex-col">
                        <ArrowUp 
                          class="w-3 h-3" 
                          :class="{ 'text-blue-600': sortField === 'period' && sortDirection === 'asc', 'text-gray-300': !(sortField === 'period' && sortDirection === 'asc') }"
                        />
                        <ArrowDown 
                          class="w-3 h-3 -mt-1" 
                          :class="{ 'text-blue-600': sortField === 'period' && sortDirection === 'desc', 'text-gray-300': !(sortField === 'period' && sortDirection === 'desc') }"
                        />
                      </div>
                    </div>
                  </th>
                  <th
                    @click="sort('target_amount')"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                  >
                    <div class="flex items-center gap-2">
                      <span>Target Amount</span>
                      <div class="flex flex-col">
                        <ArrowUp 
                          class="w-3 h-3" 
                          :class="{ 'text-blue-600': sortField === 'target_amount' && sortDirection === 'asc', 'text-gray-300': !(sortField === 'target_amount' && sortDirection === 'asc') }"
                        />
                        <ArrowDown 
                          class="w-3 h-3 -mt-1" 
                          :class="{ 'text-blue-600': sortField === 'target_amount' && sortDirection === 'desc', 'text-gray-300': !(sortField === 'target_amount' && sortDirection === 'desc') }"
                        />
                      </div>
                    </div>
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="donation in targetDonations.data" :key="donation.id" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">{{ donation.period }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">${{ Number(donation.target_amount).toLocaleString() }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(donation.start_date) }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(donation.end_date) }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-right">
                    <div class="flex justify-end gap-2">
                      <button
                        @click="router.get(route('target-donations.show', donation.id))"
                        class="p-1.5 bg-blue-100 text-blue-600 rounded-md hover:bg-blue-200 transition-colors"
                        title="View"
                      >
                        <Eye class="w-4 h-4" />
                      </button>
                      <button
                        @click="router.get(route('target-donations.edit', donation.id))"
                        class="p-1.5 bg-amber-100 text-amber-600 rounded-md hover:bg-amber-200 transition-colors"
                        title="Edit"
                      >
                        <Edit class="w-4 h-4" />
                      </button>
                      <button
                        @click="deleteDonation(donation.id)"
                        class="p-1.5 bg-red-100 text-red-600 rounded-md hover:bg-red-200 transition-colors"
                        title="Delete"
                      >
                        <Trash class="w-4 h-4" />
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="px-6 py-4 border-t" v-if="targetDonations && targetDonations.data && targetDonations.data.length">
            <div class="flex items-center justify-between">
              <div class="text-sm text-gray-500">
                Showing {{ targetDonations.from || 0 }} to {{ targetDonations.to || 0 }} of {{ targetDonations.total || 0 }} results
              </div>
              <nav
                class="isolate inline-flex -space-x-px rounded-md shadow-sm"
                aria-label="Pagination"
              >
                <button
                  v-for="(link, key) in targetDonations.links"
                  :key="key"
                  @click="link.url && router.get(link.url)"
                  :disabled="!link.url"
                  class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-500 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                  :class="{
                    'bg-gray-100 z-10': link.active,
                    'hidden md:inline-flex': link.label === '...' && !link.active,
                    'cursor-not-allowed opacity-50': !link.url
                  }"
                  v-html="link.label"
                >
                </button>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import AppLayout from '@/layouts/AppLayout.vue';
// Add this import if not already present
import { Printer, Download, Plus, Edit, Trash, Search, ArrowUp, ArrowDown, Eye } from 'lucide-vue-next';

type TargetDonation = {
  id: number;
  period: string;
  target_amount: number;
  start_date: string;
  end_date: string;
};

type Filters = {
  search?: string;
  field?: string;
  sort?: string;
  direction?: string;
};

const props = defineProps<{
  targetDonations: {
    data: TargetDonation[];
    total: number;
    from: number;
    to: number;
    links: { label: string; url: string | null; active: boolean }[];
  };
  filters?: Filters; // Make filters optional
}>();

// Initialize with default values if filters is undefined
const search = ref(props.filters?.search || '');
const searchField = ref(props.filters?.field || 'period');
const sortField = ref(props.filters?.sort || 'period');
const sortDirection = ref(props.filters?.direction || 'asc');

const performSearch = useDebounceFn(() => {
  router.get(route('target-donations.index'), {
    search: search.value,
    field: searchField.value,
    sort: sortField.value,
    direction: sortDirection.value,
  }, {
    preserveState: true,
    preserveScroll: true,
    replace: true,
  });
}, 500);

watch(searchField, () => {
  performSearch();
});

const sort = (field: string) => {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortField.value = field;
    sortDirection.value = 'asc';
  }

  router.get(route('target-donations.index'), {
    search: search.value,
    field: searchField.value,
    sort: sortField.value,
    direction: sortDirection.value,
  }, {
    preserveState: true,
    preserveScroll: true,
    only: ['targetDonations'],
    replace: true
  });
};

const deleteDonation = (id: number) => {
  if (confirm('Are you sure you want to delete this target donation?')) {
    router.delete(route('target-donations.destroy', id));
  }
};

const handlePrint = () => {
  window.open(route('target-donations.print'), '_blank');
};

const handleExport = () => {
  window.location.href = route('target-donations.export');
};

const breadcrumbs = ref([
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Target Donations', href: '/dashboard/target-donations' },
]);

// Add this formatting function
const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric' 
  });
};
</script>

<style scoped>
.transition-colors {
  transition: background-color 0.2s, color 0.2s;
}

tr.hover\:bg-gray-50:hover {
  background-color: #f9fafb;
}

th:hover .w-3 {
  transform: scale(1.2);
  transition: transform 0.2s;
}
</style>
  