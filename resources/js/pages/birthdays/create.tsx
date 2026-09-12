import { Head } from '@inertiajs/react';
import BirthdayForm from '@/components/birthday-form';

export default function CreateBirthday() {
    return (
        <>
            <Head title="Ajouter un anniversaire" />
            <div className="flex flex-1 flex-col gap-6 p-4 md:p-8">
                <div>
                    <h1 className="text-2xl font-semibold">
                        Ajouter un anniversaire
                    </h1>
                    <p className="text-muted-foreground">
                        Renseignez les informations de cette personne.
                    </p>
                </div>
                <BirthdayForm />
            </div>
        </>
    );
}
