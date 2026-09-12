import { useForm } from '@inertiajs/react';
import { FormEvent } from 'react';
import { toast } from 'sonner';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/input-error';

export type BirthdayData = {
    first_name: string;
    last_name: string;
    birth_date: string;
    email: string;
    photo: File | null;
    notes: string;
};

type BirthdayValue = Omit<BirthdayData, 'photo' | 'notes'> & {
    photo?: string | null;
    notes: string | null;
};

type Props = {
    birthday?: BirthdayValue & { id: number };
};

export default function BirthdayForm({ birthday }: Props) {
    const form = useForm<BirthdayData>({
        first_name: birthday?.first_name ?? '',
        last_name: birthday?.last_name ?? '',
        birth_date: birthday?.birth_date?.slice(0, 10) ?? '',
        email: birthday?.email ?? '',
        photo: null,
        notes: birthday?.notes ?? '',
    });

    const submit = (event: FormEvent) => {
        event.preventDefault();
        const options = {
            forceFormData: true,
            onError: () =>
                toast.error('Impossible d’enregistrer cet anniversaire.'),
        };
        if (birthday) {
            form.transform((data) => ({ ...data, _method: 'put' }));
            form.post(`/birthday/${birthday.id}`, options);
        } else {
            form.post('/birthday', options);
        }
    };

    return (
        <form
            onSubmit={submit}
            className="border-border grid gap-4 rounded-xl border p-5 md:grid-cols-2"
        >
            <div className="grid gap-2">
                <Label htmlFor="first_name">Prénom</Label>
                <Input
                    id="first_name"
                    value={form.data.first_name}
                    onChange={(e) => form.setData('first_name', e.target.value)}
                    required
                />
                <InputError message={form.errors.first_name} />
            </div>
            <div className="grid gap-2">
                <Label htmlFor="last_name">Nom</Label>
                <Input
                    id="last_name"
                    value={form.data.last_name}
                    onChange={(e) => form.setData('last_name', e.target.value)}
                    required
                />
                <InputError message={form.errors.last_name} />
            </div>
            <div className="grid gap-2">
                <Label htmlFor="birth_date">Date de naissance</Label>
                <Input
                    id="birth_date"
                    type="date"
                    max={new Date().toISOString().slice(0, 10)}
                    value={form.data.birth_date}
                    onChange={(e) => form.setData('birth_date', e.target.value)}
                    required
                />
                <InputError message={form.errors.birth_date} />
            </div>
            <div className="grid gap-2">
                <Label htmlFor="email">Email</Label>
                <Input
                    id="email"
                    type="email"
                    value={form.data.email}
                    onChange={(e) => form.setData('email', e.target.value)}
                    required
                />
                <InputError message={form.errors.email} />
            </div>
            <div className="grid gap-2 md:col-span-2">
                <Label htmlFor="photo">Photo</Label>
                <Input
                    id="photo"
                    type="file"
                    accept="image/*"
                    onChange={(e) =>
                        form.setData('photo', e.target.files?.[0] ?? null)
                    }
                />
                <p className="text-muted-foreground text-xs">
                    PNG, JPG, WEBP — 5 Mo maximum.
                </p>
                <InputError message={form.errors.photo} />
                {birthday?.photo && (
                    <p className="text-muted-foreground text-sm">
                        Une photo est déjà enregistrée. Choisissez un fichier
                        pour la remplacer.
                    </p>
                )}
            </div>
            <div className="grid gap-2 md:col-span-2">
                <Label htmlFor="notes">Notes (facultatif)</Label>
                <textarea
                    id="notes"
                    value={form.data.notes}
                    onChange={(e) => form.setData('notes', e.target.value)}
                    className="border-input bg-background min-h-24 rounded-md border px-3 py-2 text-sm"
                />
                <InputError message={form.errors.notes} />
            </div>
            <div className="flex gap-2 md:col-span-2">
                <Button type="submit" disabled={form.processing}>
                    {birthday
                        ? 'Enregistrer les modifications'
                        : 'Ajouter l’anniversaire'}
                </Button>
                <Button
                    type="button"
                    variant="outline"
                    onClick={() => window.history.back()}
                >
                    Annuler
                </Button>
            </div>
        </form>
    );
}
