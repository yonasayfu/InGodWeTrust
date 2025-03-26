namespace App\Exports;

use App\Models\DonationAgreement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DonationAgreementsExport implements FromCollection, WithHeadings
{
    // In DonationAgreementsExport.php
public function collection()
{
    return DonationAgreement::with(['supporter', 'bankForm'])
        ->get()
        ->map(function ($agreement) {
            return [
                $agreement->id,
                optional($agreement->supporter)->name ?? 'N/A',
                $agreement->donation_type,
                $agreement->donation_amount,
                $agreement->recurring_interval,
                optional($agreement->bankForm)->bank_name ?? 'N/A',
                $agreement->created_at->format('Y-m-d H:i:s'),
            ];
        });
}

    public function headings(): array
    {
        return [
            'ID',
            'Supporter',
            'Donation Type',
            'Amount',
            'Recurring Interval',
            'Bank',
            'Created At'
        ];
    }
}
