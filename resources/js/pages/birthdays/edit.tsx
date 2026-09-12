import { Head } from '@inertiajs/react';
import BirthdayForm from '@/components/birthday-form';

type Props = {
    birthday: {
        id: number;
        first_name: string;
        last_name: string;
        birth_date: string;
        email: string;
        photo: string | null;
        notes: string | null;
    };
};

export default function EditBirthday({ birthday }: Props) {
    return (
        <>
            <Head title="Modifier un anniversaire" />
            <div className="flex flex-1 flex-col gap-6 p-4 md:p-8">
                <div>
                    <h1 className="text-2xl font-semibold">
                        Modifier un anniversaire
                    </h1>
                    <p className="text-muted-foreground">
                        Mettez à jour les informations.
                    </p>
                </div>
                <BirthdayForm birthday={birthday} />
            </div>
        </>
    );
}
