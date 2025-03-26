<script setup lang="ts">
import { ref, watch, computed } from "vue";
import { Link, router } from "@inertiajs/vue3";
import { useDebounceFn } from "@vueuse/core";
import AppLayout from "@/layouts/AppLayout.vue";
import { Button } from "@/components/ui/button";
import {
  Printer,
  Download,
  Plus,
  Eye,
  Edit,
  Trash,
  Search,
  ArrowUp,
  ArrowDown,
  Handshake,
  FileText,
  Home,
} from "lucide-vue-next"; // Added relevant icons

// Define the type for a Donation Agreement based on the schema [1, 2]
type DonationAgreement = {
  id: number;
  supporter_id: number;
  supporter: { id: number; name: string }; // Assuming the controller will provide the related supporter data
  donation_type: string;
  donation_amount: number;
  recurring_interval: string;
  bank_id: number;
  bank_form: { id: number; bank_name: string }; // Assuming the controller will provide the related bank form data
  signed_agreement_pdf: string | null;
  summary_pdf: string | null;
  created_at: string;
  updated_at: string;
};

type Filters = {
  search?: string;
  field?: string;
  sort?: string;
  direction?: string;
};

const props = defineProps<{
  donationAgreements: {
    data: DonationAgreement[];
    total: number;
    from: number;
    to: number;
    links: { label: string; url: string | null; active: boolean }[];
  };
  filters: Filters;
}>();

const search = ref(props.filters.search || "");
const searchField = ref(props.filters.field || "supporter.name"); // Default search field
const sortField = ref(props.filters.sort || "supporter.name");
const sortDirection = ref(props.filters.direction || "asc");

const performSearch = useDebounceFn(() => {
  router.get(
    route("donation-agreements.index"),
    {
      // Assuming your route name is 'donation-agreements.index'
      search: search.value,
      field: searchField.value,
      sort: sortField.value,
      direction: sortDirection.value,
    },
    {
      preserveState: true,
      preserveScroll: true,
      replace: true,
    }
  );
}, 500);

watch(search, performSearch);
watch(searchField, performSearch);

const sort = (field: string) => {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
  } else {
    sortField.value = field;
    sortDirection.value = "asc";
  }

  router.get(
    route("donation-agreements.index"),
    {
      search: search.value,
      field: searchField.value,
      sort: sortField.value,
      direction: sortDirection.value,
    },
    {
      preserveState: true,
      preserveScroll: true,
      only: ["donationAgreements"],
      replace: true,
    }
  );
};

const confirmDelete = (donationAgreement: DonationAgreement) => {
  if (
    confirm(
      `Are you sure you want to delete the donation agreement for ${donationAgreement.supporter.name}?`
    )
  ) {
    router.delete(route("donation-agreements.destroy", donationAgreement.id), {
      preserveScroll: true,
    }); // Assuming your destroy route is 'donation-agreements.destroy'
  }
};

// Placeholder functions for print and export - implement these in your controller if needed [3]
const handlePrint = () => {
  // Implement print functionality
  console.log("Print Donation Agreements");
};

const handleExport = () => {
  // Implement export functionality
  console.log("Export Donation Agreements");
};

const breadcrumbs = [
  { title: "Dashboard", href: "/dashboard" },
  { title: "Donation Agreements", href: "/dashboard/donation-agreements" }, // Adjust the href as needed
];
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Donation Agreements</h1>
        <div class="flex items-center gap-4">
          <div class="relative flex gap-2 items-center">
            <label for="search-field" class="text-sm font-medium text-gray-600"
              >Search by:</label
            >
            <select
              id="search-field"
              v-model="searchField"
              class="border rounded-lg px-3 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500"
            >
              <option value="supporter.name">Supporter Name</option>
              <option value="donation_type">Donation Type</option>
              <option value="recurring_interval">Recurring Interval</option>
              <option value="bank_form.bank_name">Bank Name</option>
              <!-- Add other relevant fields for searching -->
            </select>
            <div class="relative flex-1">
              <Search
                class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"
              />
              <input
                v-model="search"
                type="search"
                placeholder="Search donation agreements..."
                class="pl-10 pr-4 py-2 border rounded-lg w-full focus:ring-2 focus:ring-blue-500"
                @input="performSearch"
              />
            </div>
          </div>
          <Link :href="route('donation-agreements.create')" class="inline-flex">
            <Button
              variant="success"
              class="flex items-center gap-2 px-4 py-2 rounded-lg shadow-md transition-all duration-300 bg-emerald-500 hover:bg-emerald-600 text-white hover:shadow-lg"
            >
              <Plus class="w-5 h-5" />
              <span class="font-semibold">Add New Agreement</span>
            </Button>
          </Link>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="flex gap-4 p-4 border-b">
          <Button
            variant="secondary"
            class="flex items-center gap-2"
            @click="handlePrint"
          >
            <Printer class="w-5 h-5" />
            <span>Print</span>
          </Button>
          <Button
            variant="primary"
            class="flex items-center gap-2"
            @click="handleExport"
          >
            <Download class="w-5 h-5" />
            <span>Export</span>
          </Button>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="bg-gray-50 border-b">
                <th
                  @click="sort('supporter.name')"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                >
                  <div class="flex items-center gap-2">
                    <span>Supporter Name</span>
                    <div class="flex flex-col">
                      <ArrowUp
                        class="w-3 h-3"
                        :class="{
                          'text-blue-600':
                            sortField === 'supporter.name' &&
                            sortDirection === 'asc',
                          'text-gray-300': !(
                            sortField === 'supporter.name' &&
                            sortDirection === 'asc'
                          ),
                        }"
                      />
                      <ArrowDown
                        class="w-3 h-3 -mt-1"
                        :class="{
                          'text-blue-600':
                            sortField === 'supporter.name' &&
                            sortDirection === 'desc',
                          'text-gray-300': !(
                            sortField === 'supporter.name' &&
                            sortDirection === 'desc'
                          ),
                        }"
                      />
                    </div>
                  </div>
                </th>
                <th
                  @click="sort('donation_type')"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                >
                  <div class="flex items-center gap-2">
                    <span>Donation Type</span>
                    <div class="flex flex-col">
                      <ArrowUp
                        class="w-3 h-3"
                        :class="{
                          'text-blue-600':
                            sortField === 'donation_type' &&
                            sortDirection === 'asc',
                          'text-gray-300': !(
                            sortField === 'donation_type' &&
                            sortDirection === 'asc'
                          ),
                        }"
                      />
                      <ArrowDown
                        class="w-3 h-3 -mt-1"
                        :class="{
                          'text-blue-600':
                            sortField === 'donation_type' &&
                            sortDirection === 'desc',
                          'text-gray-300': !(
                            sortField === 'donation_type' &&
                            sortDirection === 'desc'
                          ),
                        }"
                      />
                    </div>
                  </div>
                </th>
                <th
                  @click="sort('donation_amount')"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                >
                  <div class="flex items-center gap-2">
                    <span>Amount</span>
                    <div class="flex flex-col">
                      <ArrowUp
                        class="w-3 h-3"
                        :class="{
                          'text-blue-600':
                            sortField === 'donation_amount' &&
                            sortDirection === 'asc',
                          'text-gray-300': !(
                            sortField === 'donation_amount' &&
                            sortDirection === 'asc'
                          ),
                        }"
                      />
                      <ArrowDown
                        class="w-3 h-3 -mt-1"
                        :class="{
                          'text-blue-600':
                            sortField === 'donation_amount' &&
                            sortDirection === 'desc',
                          'text-gray-300': !(
                            sortField === 'donation_amount' &&
                            sortDirection === 'desc'
                          ),
                        }"
                      />
                    </div>
                  </div>
                </th>
                <th
                  @click="sort('recurring_interval')"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                >
                  <div class="flex items-center gap-2">
                    <span>Recurring Interval</span>
                    <div class="flex flex-col">
                      <ArrowUp
                        class="w-3 h-3"
                        :class="{
                          'text-blue-600':
                            sortField === 'recurring_interval' &&
                            sortDirection === 'asc',
                          'text-gray-300': !(
                            sortField === 'recurring_interval' &&
                            sortDirection === 'asc'
                          ),
                        }"
                      />
                      <ArrowDown
                        class="w-3 h-3 -mt-1"
                        :class="{
                          'text-blue-600':
                            sortField === 'recurring_interval' &&
                            sortDirection === 'desc',
                          'text-gray-300': !(
                            sortField === 'recurring_interval' &&
                            sortDirection === 'desc'
                          ),
                        }"
                      />
                    </div>
                  </div>
                </th>
                <th
                  @click="sort('bank_form.bank_name')"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                >
                  <div class="flex items-center gap-2">
                    <span>Bank Name</span>
                    <div class="flex flex-col">
                      <ArrowUp
                        class="w-3 h-3"
                        :class="{
                          'text-blue-600':
                            sortField === 'bank_form.bank_name' &&
                            sortDirection === 'asc',
                          'text-gray-300': !(
                            sortField === 'bank_form.bank_name' &&
                            sortDirection === 'asc'
                          ),
                        }"
                      />
                      <ArrowDown
                        class="w-3 h-3 -mt-1"
                        :class="{
                          'text-blue-600':
                            sortField === 'bank_form.bank_name' &&
                            sortDirection === 'desc',
                          'text-gray-300': !(
                            sortField === 'bank_form.bank_name' &&
                            sortDirection === 'desc'
                          ),
                        }"
                      />
                    </div>
                  </div>
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Signed Agreement
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Summary PDF
                </th>
                <th
                  class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr
                v-for="agreement in donationAgreements.data"
                :key="agreement.id"
                class="hover:bg-gray-50"
              >
                <td class="px-6 py-4 whitespace-nowrap">
                  <Link
                    :href="route('supporters.show', agreement.supporter_id)"
                    class="text-blue-600 hover:underline"
                  >
                    {{ agreement.supporter.name }}
                  </Link>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ agreement.donation_type }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  ${{ agreement.donation_amount }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ agreement.recurring_interval }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <Link
                    :href="route('bank-forms.edit', agreement.bank_id)"
                    class="text-blue-600 hover:underline"
                  >
                    {{ agreement.bank_form.bank_name }}
                  </Link>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <a
                    v-if="agreement.signed_agreement_pdf"
                    :href="'/storage/' + agreement.signed_agreement_pdf"
                    target="_blank"
                    class="text-green-600 hover:underline"
                    >View</a
                  >
                  <span v-else class="text-gray-500">N/A</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <a
                    v-if="agreement.summary_pdf"
                    :href="'/storage/' + agreement.summary_pdf"
                    target="_blank"
                    class="text-green-600 hover:underline"
                    >View</a
                  >
                  <span v-else class="text-gray-500">N/A</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right">
                  <div class="flex justify-end gap-2">
                    <Link
                      :href="route('donation-agreements.edit', agreement.id)"
                      class="p-1.5 bg-amber-100 text-amber-600 rounded-md hover:bg-amber-200 transition-colors"
                      title="Edit"
                    >
                      <Edit class="w-4 h-4" />
                    </Link>
                    <button
                      @click="confirmDelete(agreement)"
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

        <div
          class="px-6 py-4 border-t"
          v-if="
            donationAgreements &&
            donationAgreements.data &&
            donationAgreements.data.length
          "
        >
          <div class="flex items-center justify-between">
            <div class="text-sm text-gray-500">
              Showing {{ donationAgreements.from || 0 }} to
              {{ donationAgreements.to || 0 }} of
              {{ donationAgreements.total || 0 }} results
            </div>
            <nav
              class="isolate inline-flex -space-x-px rounded-md shadow-sm"
              aria-label="Pagination"
            >
              <Link
                v-for="(link, key) in donationAgreements.links"
                :key="key"
                :href="link.url"
                aria-current="page"
                class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-500 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                :class="{
                  'bg-gray-100 z-10': link.active,
                  'hidden md:inline-flex': link.label === '...' && !link.active,
                  'relative inline-flex items-center px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0':
                    link.label === 'Previous',
                  'relative inline-flex items-center px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0':
                    link.label === 'Next',
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
/* CSS comments should use this format */
.transition-colors {
  transition:
    background-color 0.2s,
    color 0.2s;
}

tr.hover\:bg-gray-50:hover {
  background-color: #f9fafb;
}

th:hover .w-3 {
  transform: scale(1.2);
  transition: transform 0.2s;
}
</style>
