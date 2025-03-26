<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ArrowLeft, FileText } from 'lucide-vue-next';
import { ref } from 'vue';
import { useToast } from '@/composables/useToast';

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
    donationAgreement: DonationAgreement;
    supporters: { id: number; name: string }[]; // List of supporters for dropdown
    bankForms: { id: number; bank_name: string; form_name: string }[]; // List of bank forms for dropdown
}>();

const breadcrumbs = ref([
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Donation Agreements', href: route('donation-agreements.index') },
    { title: 'Edit Agreement', href: route('donation-agreements.edit', props.donationAgreement.id) },
]);

const isSubmitting = ref(false);
const { toast } = useToast();

const form = useForm({
    supporter_id: props.donationAgreement.supporter_id,
    donation_type: props.donationAgreement.donation_type,
    donation_amount: props.donationAgreement.donation_amount,
    recurring_interval: props.donationAgreement.recurring_interval,
    bank_id: props.donationAgreement.bank_id,
    _method: 'PUT',  // Removed null file initializations
});

const submit = () => {
    isSubmitting.value = true;
    form.post(route('donation-agreements.update', props.donationAgreement.id), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            isSubmitting.value = false;
            toast.success('Donation Agreement updated successfully!');
            router.visit(route('donation-agreements.index'), { replace: true });
        },
        onError: (errors) => {
            isSubmitting.value = false;
            toast.error('Failed to update Donation Agreement.');
            console.error('Update errors:', errors);
        },
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 md:p-6 max-w-3xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-xl font-semibold text-gray-800">Edit Donation Agreement</h1>
                    <Link :href="route('donation-agreements.index')" class="text-blue-500 hover:underline">
                        <ArrowLeft class="w-4 h-4 inline-block mr-1" /> Back to List
                    </Link>
                </div>
                <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-4">
                    <div>
                        <label for="supporter_id" class="block text-sm font-medium text-gray-700">Supporter</label>
                        <select v-model="form.supporter_id" id="supporter_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="" disabled>Select Supporter</option>
                            <option v-for="supporter in supporters" :key="supporter.id" :value="supporter.id">
                                {{ supporter.name }}
                            </option>
                        </select>
                        <div v-if="form.errors.supporter_id" class="text-sm text-red-600">{{ form.errors.supporter_id }}</div>
                    </div>
                    <div>
                        <label for="donation_type" class="block text-sm font-medium text-gray-700">Donation Type</label>
                        <input v-model="form.donation_type" type="text" id="donation_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <div v-if="form.errors.donation_type" class="text-sm text-red-600">{{ form.errors.donation_type }}</div>
                    </div>
                    <div>
                        <label for="donation_amount" class="block text-sm font-medium text-gray-700">Donation Amount</label>
                        <input v-model="form.donation_amount" type="number" step="0.01" id="donation_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <div v-if="form.errors.donation_amount" class="text-sm text-red-600">{{ form.errors.donation_amount }}</div>
                    </div>
                    <div>
                        <label for="recurring_interval" class="block text-sm font-medium text-gray-700">Recurring Interval</label>
                        <input v-model="form.recurring_interval" type="text" id="recurring_interval" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <div v-if="form.errors.recurring_interval" class="text-sm text-red-600">{{ form.errors.recurring_interval }}</div>
                    </div>
                    <div>
                        <label for="bank_id" class="block text-sm font-medium text-gray-700">Bank Form</label>
                        <select v-model="form.bank_id" id="bank_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="" disabled>Select Bank Form</option>
                            <option v-for="bankForm in bankForms" :key="bankForm.id" :value="bankForm.id">
                                {{ bankForm.bank_name }} - {{ bankForm.form_name }}
                            </option>
                        </select>
                        <div v-if="form.errors.bank_id" class="text-sm text-red-600">{{ form.errors.bank_id }}</div>
                    </div>
                    <div>
                        // In template section for file inputs:
                        <div>
                            <label for="signed_agreement_pdf" class="block text-sm font-medium text-gray-700">Signed Agreement PDF</label>
                            <input type="file" id="signed_agreement_pdf" @input="form.signed_agreement_pdf = $event.target.files[0]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <div v-if="form.errors.signed_agreement_pdf" class="text-sm text-red-600">{{ form.errors.signed_agreement_pdf }}</div>
                            <a v-if="props.donationAgreement.signed_agreement_pdf" :href="props.donationAgreement.signed_agreement_pdf" target="_blank" class="text-blue-500 hover:underline text-sm mt-1 inline-block">
                                <FileText class="w-4 h-4 inline-block mr-1" /> View Current File
                            </a>
                        </div>
                        <div>
                            <label for="summary_pdf" class="block text-sm font-medium text-gray-700">Summary PDF</label>
                            <input type="file" id="summary_pdf" @input="form.summary_pdf = $event.target.files[0]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <div v-if="form.errors.summary_pdf" class="text-sm text-red-600">{{ form.errors.summary_pdf }}</div>
                            <a v-if="props.donationAgreement.summary_pdf" :href="props.donationAgreement.summary_pdf" target="_blank" class="text-blue-500 hover:underline text-sm mt-1 inline-block">
                                <FileText class="w-4 h-4 inline-block mr-1" /> View Current File
                            </a>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" :disabled="isSubmitting" class="px-4 py-2 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <span v-if="isSubmitting" class="animate-spin mr-2"></span>
                                Update Agreement
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>