
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Head, Link } from '@inertiajs/react';
import { route } from 'ziggy-js';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Index',
        href: '/index',
    },
];


export default function Index() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Index" />
            <div className='m-4'>
                <Link href={route('todos.create')}><Button>Create Todo</Button></Link>
            </div>
            <div className='m-4'>
                <a href="/todos/datatable">
                    <Button>Go to Datatable</Button>
                </a>
            </div>





        </AppLayout>
    );
}
