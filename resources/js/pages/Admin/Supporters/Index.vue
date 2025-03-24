<script setup lang="ts">
import { ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Printer, Download, Plus, Eye, Edit, Trash, Search, ArrowUp, ArrowDown } from 'lucide-vue-next';

// Define types for better type safety
type Supporter = {
  id: number;
  name: string;
  phone_number: string;
  email: string;
  support_type: string;
  contribution_amount: number;
};

type Filters = {
  search?: string;
  field?: string;
  sort?: string;
  direction?: string;
};

// Component props
const props = defineProps<{
  supporters: {
    data: Supporter[];
    total: number;
    from: number;
    to: number;
    links: { label: string; url: string | null; active: boolean }[];
  };
  filters: Filters;
}>();

// Initialize reactive state with values from props or defaults
const search = ref(props.filters.search || '');
const searchField = ref(props.filters.field || 'name');
const sortField = ref(props.filters.sort || 'name');
const sortDirection = ref(props.filters.direction || 'asc');

/**
 * Perform search with debounce to prevent excessive API calls
 */
const performSearch = useDebounceFn(() => {
  router.get(route('supporters.index'), {
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

// Trigger search when search field changes
watch(searchField, () => {
  performSearch();
});

/**
 * Handle sorting when a column header is clicked
 */
const sort = (field: string) => {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortField.value = field;
    sortDirection.value = 'asc';
  }

  router.get(route('supporters.index'), {
    search: search.value,
    field: searchField.value,
    sort: sortField.value,
    direction: sortDirection.value,
  }, {
    preserveState: true,
    preserveScroll: true,
    only: ['supporters'],
    replace: true
  });
};

/**
 * Confirm and delete a supporter
 */
const confirmDelete = (supporter: Supporter) => {
  if (confirm(`Are you sure you want to delete ${supporter.name}?`)) {
    router.delete(route('supporters.destroy', supporter.id));
  }
};

/**
 * Open print view in a new tab
 */
const handlePrint = () => {
  window.open(route('supporters.print'), '_blank');
};

/**
 * Download supporters data as CSV
 */
const handleExport = () => {
  window.location.href = route('supporters.export');
};

// Define breadcrumbs for navigation
const breadcrumbs = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Supporters', href: '/dashboard/supporters' },
];
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <!-- Header with title and search -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Supporters</h1>
        <div class="flex items-center gap-4">
          <!-- Search with field selection -->
          <div class="relative flex gap-2 items-center">
            <label for="search-field" class="text-sm font-medium text-gray-600">Search by:</label>
            <select
              id="search-field"
              v-model="searchField"
              class="border rounded-lg px-3 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500"
            >
              <option value="name">Name</option>
              <option value="phone_number">Phone Number</option>
              <option value="support_type">Support Type</option>
            </select>
            <div class="relative flex-1">
              <Search class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
              <input
                v-model="search"
                type="search"
                placeholder="Search supporters..."
                class="pl-10 pr-4 py-2 border rounded-lg w-full focus:ring-2 focus:ring-blue-500"
                @input="performSearch"
              />
            </div>
          </div>
          <!-- Add new supporter button -->
          <Link :href="route('supporters.create')" class="inline-flex">
            <Button 
              variant="success" 
              class="flex items-center gap-2 px-4 py-2 rounded-lg shadow-md transition-all duration-300 bg-emerald-500 hover:bg-emerald-600 text-white hover:shadow-lg"
            >
              <Plus class="w-5 h-5" />
              <span class="font-semibold">Add New Supporter</span>
            </Button>
          </Link>
        </div>
      </div>

      <!-- Main content card -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Action buttons -->
        <div class="flex gap-4 p-4 border-b">
          <Button variant="secondary" class="flex items-center gap-2" @click="handlePrint">
            <Printer class="w-5 h-5" />
            <span>Print</span>
          </Button>
          <Button variant="primary" class="flex items-center gap-2" @click="handleExport">
            <Download class="w-5 h-5" />
            <span>Export</span>
          </Button>
        </div>

        <!-- Table of supporters -->
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="bg-gray-50 border-b">
                <!-- Sortable column headers -->
                <th
                  @click="sort('name')"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                >
                  <div class="flex items-center gap-2">
                    <span>Name</span>
                    <div class="flex flex-col">
                      <ArrowUp 
                        class="w-3 h-3" 
                        :class="{ 'text-blue-600': sortField === 'name' && sortDirection === 'asc', 'text-gray-300': !(sortField === 'name' && sortDirection === 'asc') }"
                      />
                      <ArrowDown 
                        class="w-3 h-3 -mt-1" 
                        :class="{ 'text-blue-600': sortField === 'name' && sortDirection === 'desc', 'text-gray-300': !(sortField === 'name' && sortDirection === 'desc') }"
                      />
                    </div>
                  </div>
                </th>
                <th
                  @click="sort('email')"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                >
                  <div class="flex items-center gap-2">
                    <span>Email</span>
                    <div class="flex flex-col">
                      <ArrowUp 
                        class="w-3 h-3" 
                        :class="{ 'text-blue-600': sortField === 'email' && sortDirection === 'asc', 'text-gray-300': !(sortField === 'email' && sortDirection === 'asc') }"
                      />
                      <ArrowDown 
                        class="w-3 h-3 -mt-1" 
                        :class="{ 'text-blue-600': sortField === 'email' && sortDirection === 'desc', 'text-gray-300': !(sortField === 'email' && sortDirection === 'desc') }"
                      />
                    </div>
                  </div>
                </th>
                <th
                  @click="sort('support_type')"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                >
                  <div class="flex items-center gap-2">
                    <span>Support Type</span>
                    <div class="flex flex-col">
                      <ArrowUp 
                        class="w-3 h-3" 
                        :class="{ 'text-blue-600': sortField === 'support_type' && sortDirection === 'asc', 'text-gray-300': !(sortField === 'support_type' && sortDirection === 'asc') }"
                      />
                      <ArrowDown 
                        class="w-3 h-3 -mt-1" 
                        :class="{ 'text-blue-600': sortField === 'support_type' && sortDirection === 'desc', 'text-gray-300': !(sortField === 'support_type' && sortDirection === 'desc') }"
                      />
                    </div>
                  </div>
                </th>
                <th
                  @click="sort('contribution_amount')"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                >
                  <div class="flex items-center gap-2">
                    <span>Contribution</span>
                    <div class="flex flex-col">
                      <ArrowUp 
                        class="w-3 h-3" 
                        :class="{ 'text-blue-600': sortField === 'contribution_amount' && sortDirection === 'asc', 'text-gray-300': !(sortField === 'contribution_amount' && sortDirection === 'asc') }"
                      />
                      <ArrowDown 
                        class="w-3 h-3 -mt-1" 
                        :class="{ 'text-blue-600': sortField === 'contribution_amount' && sortDirection === 'desc', 'text-gray-300': !(sortField === 'contribution_amount' && sortDirection === 'desc') }"
                      />
                    </div>
                  </div>
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="supporter in supporters.data" :key="supporter.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">{{ supporter.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ supporter.phone_number }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <!-- Color-coded support type badges -->
                  <span
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                    :class="{
                      'bg-green-100 text-green-800': supporter.support_type === 'monthly',
                      'bg-blue-100 text-blue-800': supporter.support_type === 'one-time',
                      'bg-purple-100 text-purple-800': supporter.support_type === 'recurring',
                      'bg-amber-100 text-amber-800': supporter.support_type === 'annual',
                      'bg-gray-100 text-gray-800': !['monthly', 'one-time', 'recurring', 'annual'].includes(supporter.support_type)
                    }"
                  >
                    {{ supporter.support_type }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">{{ supporter.contribution_amount }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right">
                  <div class="flex justify-end gap-2">
                    <!-- Action buttons -->
                    <Link 
                      :href="route('supporters.show', supporter.id)" 
                      class="p-1.5 bg-blue-100 text-blue-600 rounded-md hover:bg-blue-200 transition-colors"
                      title="View Details"
                    >
                      <Eye class="w-4 h-4" />
                    </Link>
                    <Link 
                      :href="route('supporters.edit', supporter.id)" 
                      class="p-1.5 bg-amber-100 text-amber-600 rounded-md hover:bg-amber-200 transition-colors"
                      title="Edit"
                    >
                      <Edit class="w-4 h-4" />
                    </Link>
                    <button
                      @click="confirmDelete(supporter)"
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

        <!-- Pagination -->
        <div class="px-6 py-4 border-t" v-if="supporters && supporters.data && supporters.data.length">
          <div class="flex items-center justify-between">
            <div class="text-sm text-gray-500">
              Showing {{ supporters.from || 0 }} to {{ supporters.to || 0 }} of {{ supporters.total || 0 }} results
            </div>
            <nav
              class="isolate inline-flex -space-x-px rounded-md shadow-sm"
              aria-label="Pagination"
            >
              <Link
                v-for="(link, key) in supporters.links"
                :key="key"
                :href="link.url || '#'"
                aria-current="page"
                class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-500 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                :class="{
                  'bg-gray-100 z-10': link.active,
                  'hidden md:inline-flex': link.label === '...' && !link.active,
                  'relative inline-flex items-center px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0':
                    link.label === 'Previous' || link.label === 'Next',
                }"
                v-html="link.label"
              >
              </Link>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
/* Add smooth transitions for interactive elements */
.transition-colors {
  transition: background-color 0.2s, color 0.2s;
}

/* Improve table row hover effect */
tr.hover\:bg-gray-50:hover {
  background-color: #f9fafb;
}

/* Add subtle animation to sort indicators */
th:hover .w-3 {
  transform: scale(1.2);
  transition: transform 0.2s;
}
</style>