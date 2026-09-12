import { Head, Link, router } from '@inertiajs/react';
import { Pencil, Plus, Trash2 } from 'lucide-react';
import { toast } from 'sonner';
import { home } from '@/routes';
import { Button } from '@/components/ui/button';

type Birthday = {
    id: number;
    first_name: string;
    last_name: string;
    birth_date: string;
    email: string;
    photo: string | null;
};
type Props = { birthdays: Birthday[] };

export default function Dashboard({ birthdays }: Props) {
    const deleteBirthday = (id: number) => {
        if (window.confirm('Supprimer cet anniversaire ?'))
            router.delete(`/birthday/${id}`, {
                onError: () =>
                    toast.error('Impossible de supprimer cet anniversaire.'),
            });
    };

    return (
        <>
            <Head title="Anniversaires" />
            <div className="flex flex-1 flex-col gap-6 p-4 md:p-8">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold">
                            Mes anniversaires
                        </h1>
                        <p className="text-muted-foreground">
                            Tous vos anniversaires importants au même endroit.
                        </p>
                    </div>
                    <Button asChild>
                        <Link href="/birthday/create">
                            <Plus className="mr-2 h-4 w-4" /> Ajouter
                        </Link>
                    </Button>
                </div>
                <div className="grid gap-3">
                    {birthdays.length === 0 ? (
                        <p className="text-muted-foreground rounded-xl border p-6 text-center">
                            Aucun anniversaire enregistré.
                        </p>
                    ) : (
                        birthdays.map((birthday) => (
                            <div
                                key={birthday.id}
                                className="border-border flex flex-col gap-3 rounded-xl border p-4 sm:flex-row sm:items-center sm:justify-between"
                            >
                                <div>
                                    <h3 className="font-medium">
                                        {birthday.first_name}{' '}
                                        {birthday.last_name}
                                    </h3>
                                    <p className="text-muted-foreground text-sm">
                                        {new Date(
                                            `${birthday.birth_date}`,
                                        ).toLocaleDateString('fr-FR', {
                                            day: 'numeric',
                                            month: 'long',
                                            year: 'numeric',
                                        })}
                                    </p>
                                    <p className="text-muted-foreground text-sm">
                                        {(() => {
                                            const birth = new Date(birthday.birth_date);
                                            const today = new Date();

                                            const months =
                                                (today.getFullYear() - birth.getFullYear()) * 12 +
                                                (today.getMonth() - birth.getMonth());

                                            return months < 12
                                                ? `${months} mois`
                                                : `${Math.floor(months / 12)} ans`;
                                        })()}
                                    </p>
                                </div>
                                <div className="flex gap-2">
                                    <Button asChild variant="outline" size="sm">
                                        <Link
                                            href={`/birthday/${birthday.id}/edit`}
                                        >
                                            <Pencil className="mr-2 h-4 w-4" />{' '}
                                            Modifier
                                        </Link>
                                    </Button>
                                    <Button
                                        type="button"
                                        variant="destructive"
                                        size="sm"
                                        onClick={() =>
                                            deleteBirthday(birthday.id)
                                        }
                                    >
                                        <Trash2 className="mr-2 h-4 w-4" />{' '}
                                        Supprimer
                                    </Button>
                                </div>
                            </div>
                        ))
                    )}
                </div>
            </div>
        </>
    );
}

Dashboard.layout = { breadcrumbs: [{ title: 'Anniversaires', href: home() }] };
