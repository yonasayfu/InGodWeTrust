<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import {
  ArrowLeft,
  FileText,
  BookUser,
  User,
  Wallet,
  Banknote,
  UploadCloud,
  Plus,
} from "lucide-vue-next";
import { ref } from "vue";
import { useToast } from "@/composables/useToast";

interface DonationAgreement {
  id: number;
  supporter_id: number;
  donation_type: string;
  donation_amount: number;
  recurring_interval: string;
  bank_id: number;
  signed_agreement_pdf?: string | null;
  summary_pdf?: string | null;
  created_at: string;
  updated_at: string;
  supporter?: { id: number; name: string }; // Assuming you might want to display supporter info
  bank_form?: { id: number; bank_name: string; form_name: string }; // Assuming you might want to display bank form info
}

const props = defineProps<{
  supporters: { id: number; name: string }[];
  bankForms: { id: number; bank_name: string; form_name: string }[];
}>(); // Removed donationAgreement prop

const breadcrumbs = ref([
  { title: "Dashboard", href: "/dashboard" },
  { title: "Donation Agreements", href: route("donation-agreements.index") },
  { title: "Create Agreement" }, // Removed ID reference
]);

const isSubmitting = ref(false);
const { toast } = useToast();

const form = useForm({
  supporter_id: "",
  donation_type: "",
  donation_amount: 0,
  recurring_interval: "monthly",
  bank_id: "",
  signed_agreement_pdf: null,
  summary_pdf: null,
  // Removed _method: 'PUT' since this is a create form
});

const submit = () => {
  isSubmitting.value = true;
  form.post(route("donation-agreements.store"), {
    // Changed to store route
    preserveScroll: true,
    onSuccess: () => {
      isSubmitting.value = false;
      toast.success("Donation Agreement created successfully!");
      router.visit(route("donation-agreements.index"));
    },
    onError: (errors) => {
      isSubmitting.value = false;
      toast.error("Failed to update Donation Agreement.");
      console.error("Update errors:", errors);
    },
  });
};
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 md:p-6 max-w-6xl mx-auto">
      <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <BookUser class="w-6 h-6 text-blue-600" />
            New Donation Agreement
          </h1>
          <Link
            :href="route('donation-agreements.index')"
            class="text-blue-500 hover:underline flex items-center"
          >
            <ArrowLeft class="w-4 h-4 mr-1" /> Back to List
          </Link>
        </div>

        <form
          @submit.prevent="submit"
          enctype="multipart/form-data"
          class="space-y-8"
        >
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="space-y-6 col-span-2">
              <!-- Supporter Information -->
              <div
                class="bg-gray-50 rounded-xl p-6 space-y-4 border border-gray-200"
              >
                <h2
                  class="text-lg font-semibold text-gray-800 flex items-center gap-2 mb-4"
                >
                  <User class="w-5 h-5 text-blue-500" />
                  Supporter & Basic Info
                </h2>
                <div class="space-y-5">
                  <div>
                    <label
                      class="block text-sm font-semibold text-gray-700 mb-2"
                    >
                      Supporter Selection <span class="text-red-500">*</span>
                    </label>
                    <select
                      v-model="form.supporter_id"
                      class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 bg-white py-2 px-3 shadow-sm"
                    >
                      <option value="" disabled>Select Supporter</option>
                      <option
                        v-for="supporter in supporters"
                        :key="supporter.id"
                        :value="supporter.id"
                      >
                        {{ supporter.name }}
                      </option>
                    </select>
                  </div>
                  <div>
                    <label
                      class="block text-sm font-semibold text-gray-700 mb-2"
                    >
                      Donation Type <span class="text-red-500">*</span>
                    </label>
                    <select v-model="form.donation_type" 
                                                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 bg-white py-2 px-3 shadow-sm">
                                            <option value="" disabled>Select Donation Type</option>
                                            <option value="One-time">One-time Donation</option>
                                            <option value="Monthly">Monthly Recurring</option>
                                            <option value="Quarterly">Quarterly Recurring</option>
                                            <option value="Annual">Annual Recurring</option>
                                            <option value="In-kind">In-kind Donation</option>
                                        </select>
                  </div>
                  <div class="grid grid-cols-2 gap-4">
                    <div>
                      <label
                        class="block text-sm font-semibold text-gray-700 mb-2"
                      >
                        Amount ($) <span class="text-red-500">*</span>
                      </label>
                      <input
                        v-model="form.donation_amount"
                        type="number"
                        step="0.01"
                        class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 bg-white py-2 px-3 shadow-sm"
                        placeholder="0.00"
                      />
                    </div>
                    <div>
                      <label
                        class="block text-sm font-semibold text-gray-700 mb-2"
                      >
                        Frequency <span class="text-red-500">*</span>
                      </label>
                      <select
                        v-model="form.recurring_interval"
                        class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 bg-white py-2 px-3 shadow-sm"
                      >
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="annually">Annually</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Bank Information -->
              <div
                class="bg-gray-50 rounded-xl p-6 space-y-4 border border-gray-200"
              >
                <h2
                  class="text-lg font-semibold text-gray-800 flex items-center gap-2 mb-4"
                >
                  <Banknote class="w-5 h-5 text-blue-500" />
                  Banking Details
                </h2>
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Bank Form Template <span class="text-red-500">*</span>
                  </label>
                  <select
                    v-model="form.bank_id"
                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 bg-white py-2 px-3 shadow-sm"
                  >
                    <option value="" disabled>Select Bank Form</option>
                    <option
                      v-for="bankForm in bankForms"
                      :key="bankForm.id"
                      :value="bankForm.id"
                    >
                      {{ bankForm.bank_name }} - {{ bankForm.form_name }}.pdf
                    </option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Right Column - Document Uploads -->
            <div class="space-y-6">
              <div
                class="bg-gray-50 rounded-xl p-6 space-y-5 border border-gray-200"
              >
                <h2
                  class="text-lg font-semibold text-gray-800 flex items-center gap-2 mb-4"
                >
                  <FileText class="w-5 h-5 text-blue-500" />
                  Document Attachments
                </h2>
                <div class="space-y-5">
                  <!-- Signed Agreement -->
                  <div>
                    <label
                      class="block text-sm font-semibold text-gray-700 mb-3"
                    >
                      Signed Agreement <span class="text-red-500">*</span>
                    </label>
                    <div
                      class="relative group w-full border-2 border-dashed border-gray-300 rounded-xl hover:border-blue-400 bg-white transition-all p-5"
                    >
                      <input
                        type="file"
                        @input="
                          form.signed_agreement_pdf = $event.target.files[0]
                        "
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                      />
                      <div class="text-center space-y-2">
                        <UploadCloud class="w-8 h-8 text-gray-500 mx-auto" />
                        <p class="text-sm text-gray-600 font-medium">
                          Drag & drop or<br />
                          <span class="text-blue-600">browse files</span>
                        </p>
                        <p class="text-xs text-gray-400">PDF, 5MB max</p>
                      </div>
                    </div>
                  </div>

                  <!-- Summary Document -->
                  <div>
                    <label
                      class="block text-sm font-semibold text-gray-700 mb-3"
                    >
                      Summary Document
                    </label>
                    <div
                      class="relative group w-full border-2 border-dashed border-gray-300 rounded-xl hover:border-blue-400 bg-white transition-all p-5"
                    >
                      <input
                        type="file"
                        @input="form.summary_pdf = $event.target.files[0]"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                      />
                      <div class="text-center space-y-2">
                        <UploadCloud class="w-8 h-8 text-gray-500 mx-auto" />
                        <p class="text-sm text-gray-600 font-medium">
                          Drag & drop or<br />
                          <span class="text-blue-600">browse files</span>
                        </p>
                        <p class="text-xs text-gray-400">PDF, 5MB max</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="mt-8 border-t pt-6">
            <div class="flex justify-end">
              <button
                type="submit"
                :disabled="isSubmitting"
                class="w-full lg:w-auto px-8 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2 text-base font-semibold"
              >
                <span
                  v-if="isSubmitting"
                  class="inline-block w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"
                ></span>
                <Plus v-else class="w-5 h-5 text-white" />
                {{
                  isSubmitting
                    ? "Finalizing Agreement..."
                    : "Create Donation Agreement"
                }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
