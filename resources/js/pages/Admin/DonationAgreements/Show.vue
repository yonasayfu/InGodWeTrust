<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { ArrowLeft, Edit, Printer, FilePdf } from 'lucide-vue-next';

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
    supporter?: { id: number; name: string; email: string }; // Include relevant supporter details
    bank_form?: { id: number; bank_name: string; form_name: string }; // Include relevant bank form details
}

const props = defineProps<{
    donationAgreement: DonationAgreement;
}>();

const handlePrint = (id: number) => {
    const printRoute = route('donation-agreements.print-show', id);
    window.open(printRoute, '_blank');
};

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Donation Agreements', href: route('donation-agreements.index') },
    { title: 'View Details', href: null },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-4">
                    <Link :href="route('donation-agreements.index')" class="text-gray-600 hover:text-gray-900">
                        <ArrowLeft class="w-5 h-5" />
                    </Link>
                    <h1 class="text-3xl font-bold">Donation Agreement Details</h1>
                </div>
                <div class="flex items-center gap-3">
                    <Button variant="outline" class="flex items-center gap-2" @click="handlePrint(donationAgreement.id)">
                        <Printer class="w-4 h-4" />
                        <span>Print</span>
                    </Button>
                    <Link :href="route('donation-agreements.edit', donationAgreement.id)">
                        <Button variant="primary" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700">
                            <Edit class="w-4 h-4" />
                            <span>Edit</span>
                        </Button>
                    </Link>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2 border-b pb-2">Agreement Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Agreement ID:</span>
                                <span class="font-medium">{{ donationAgreement.id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Donation Type:</span>
                                <span class="font-medium">{{ donationAgreement.donation_type }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Donation Amount:</span>
                                <span class="font-medium">${{ parseFloat(donationAgreement.donation_amount).toFixed(2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Recurring Interval:</span>
                                <span class="font-medium">{{ donationAgreement.recurring_interval }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2 border-b pb-2">Related Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Supporter:</span>
                                <span class="font-medium">
                                    <Link v-if="donationAgreement.supporter" :href="route('supporters.show', donationAgreement.supporter_id)" class="text-blue-500 hover:underline">
                                        {{ donationAgreement.supporter?.name }} (ID: {{ donationAgreement.supporter_id }})
                                    </Link>
                                    <span v-else>N/A</span>
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Bank Form:</span>
                                <span class="font-medium">
                                    <span v-if="donationAgreement.bank_form">{{ donationAgreement.bank_form.bank_name }} - {{ donationAgreement.bank_form.form_name }} (ID: {{ donationAgreement.bank_id }})</span>
                                    <span v-else>N/A</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2 border-b pb-2">Attached Documents</h3>
                    <div class="space-y-3">
                        <div v-if="donationAgreement.signed_agreement_pdf">
                            <span class="text-gray-500">Signed Agreement PDF:</span>
                            <Link :href="donationAgreement.signed_agreement_pdf" target="_blank" class="text-blue-500 hover:underline flex items-center gap-1">
                                <FilePdf class="w-4 h-4" /> View File
                            </Link>
                        </div>
                        <div v-else class="text-gray-500">No signed agreement PDF uploaded.</div>

                        <div v-if="donationAgreement.summary_pdf">
                            <span class="text-gray-500">Summary PDF:</span>
                            <Link :href="donationAgreement.summary_pdf" target="_blank" class="text-blue-500 hover:underline flex items-center gap-1">
                                <FilePdf class="w-4 h-4" /> View File
                            </Link>
                        </div>
                        <div v-else class="text-gray-500">No summary PDF uploaded.</div>
                    </div>
                </div>

                <div class="p-6 border-t">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Created At: {{ new Date(donationAgreement.created_at).toLocaleString() }}</span>
                        <span class="text-gray-500">Updated At: {{ new Date(donationAgreement.updated_at).toLocaleString() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>