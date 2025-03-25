<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <!-- Header and Search -->
      <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4">
          <h1 class="text-3xl font-bold">Bank Forms</h1>
          <div class="relative">
            <input
              type="text"
              v-model="search"
              placeholder="Search by bank name..."
              class="pl-3 pr-10 py-2 border-2 border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500"
              @input="handleSearch"
            />
            <Search class="w-5 h-5 text-gray-400 absolute right-3 top-2.5" />
          </div>
        </div>
        <div>
          <Link :href="route('bank-forms.create')">
            <button class="px-4 py-2 bg-emerald-500 text-white rounded hover:bg-emerald-600 transition">Add New Bank Form</button>
          </Link>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bank Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Form Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Form File</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="(form, index) in bankForms.data" :key="form.id">
              <td class="px-6 py-4 whitespace-nowrap">{{ index + 1 }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ form.bank_name }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ form.form_name }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :title="form.form_file">{{ getShortFileName(form.form_file) }}</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex justify-end space-x-2">
                  <Link :href="route('bank-forms.show', form.id)" class="flex items-center text-blue-600 hover:text-blue-900">
                    <FileText class="w-5 h-5 mr-1" />
                    <span>View</span>
                  </Link>
                  <Link :href="route('bank-forms.edit', form.id)" class="flex items-center text-amber-600 hover:text-amber-900">
                    <Pencil class="w-5 h-5 mr-1" />
                    <span>Edit</span>
                  </Link>
                  <button @click="deleteForm(form.id)" class="flex items-center text-red-600 hover:text-red-900">
                    <Trash2 class="w-5 h-5 mr-1" />
                    <span>Delete</span>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <!-- Replace the existing pagination section with this -->
        <div class="px-6 py-4 border-t" v-if="bankForms && bankForms.data && bankForms.data.length">
          <div class="flex items-center justify-between">
            <div class="text-sm text-gray-500">
              Showing {{ bankForms.from || 0 }} to {{ bankForms.to || 0 }} of {{ bankForms.total || 0 }} results
            </div>
            <nav
              class="isolate inline-flex -space-x-px rounded-md shadow-sm"
              aria-label="Pagination"
            >
              <Link
                v-for="(link, key) in bankForms.links"
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

<script setup lang="ts">
import { ref, watch } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { FileText, Pencil, Trash2, Search } from 'lucide-vue-next';
import debounce from 'lodash/debounce';

// Define the BankForm type if not already defined in @/types
interface BankForm {
  id: number;
  bank_name: string;
  form_name: string;
  form_file: string;
  form_file_url?: string;
  created_at: string;
  updated_at: string;
}

const props = defineProps<{
  bankForms: {
    data: BankForm[];
    from: number;
    to: number;
    total: number;
    links: { label: string; url: string | null; active: boolean }[];
  }
}>();

const breadcrumbs = ref([
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Bank Forms', href: null },
]);

const search = ref('');

const handleSearch = debounce(() => {
  router.get(
    route('bank-forms.index'),
    { search: search.value },
    {
      preserveState: true,
      preserveScroll: true,
      replace: true,
    }
  );
}, 300);

function deleteForm(id: number) {
  if(confirm('Are you sure you want to delete this bank form?')) {
    router.delete(route('bank-forms.destroy', id));
  }
}

// Add this function to get shortened file name
function getShortFileName(fullPath: string): string {
  // Extract just the filename from the path
  const fileName = fullPath.split('/').pop() || '';
  // If filename is longer than 20 characters, truncate it
  if (fileName.length > 20) {
    const extension = fileName.split('.').pop();
    return fileName.substring(0, 17) + '...' + (extension ? '.' + extension : '');
  }
  return fileName;
}
</script>
