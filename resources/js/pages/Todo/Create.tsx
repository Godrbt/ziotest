

import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Head, Link, useForm } from '@inertiajs/react';
import { route } from 'ziggy-js';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Input } from '@/components/ui/input';
import { Checkbox } from '@/components/ui/checkbox';
import {CircleAlert} from "lucide-react";


"use client"

import * as React from "react"
import { ChevronDownIcon } from "lucide-react"
import { Calendar } from "@/components/ui/calendar"
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/components/ui/popover"
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';

type Calendar22Props = {
    value: Date | null
    onChange: (date: Date | null) => void
}
export function Calendar22({ value, onChange }: Calendar22Props) {
    const [open, setOpen] = React.useState(false)

    return (
        <div className="flex flex-col gap-3">
            <Label className="px-1">Due Date</Label>

            <Popover open={open} onOpenChange={setOpen}>
                <PopoverTrigger asChild>
                    <Button
                        variant="outline"
                        className="w-48 justify-between font-normal"
                    >
                        {value ? value.toLocaleDateString() : "Select date"}
                        <ChevronDownIcon />
                    </Button>
                </PopoverTrigger>

                <PopoverContent className="w-auto overflow-hidden p-0" align="start">
                    <Calendar
                        mode="single"
                        selected={value ?? undefined}
                        captionLayout="dropdown"
                        onSelect={(date) => {
                            onChange(date ?? null)
                            setOpen(false)
                        }}
                    />
                </PopoverContent>
            </Popover>
        </div>
    )
}



const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create',
        href: '/todos/create',
    },
];

export default function Create() {
    const { data, setData, post, transform, processing, errors } = useForm({
        name: '',
        description: '',
        file: null as File | null,
        priority: null as 'low' | 'medium' | 'high' | null,
        due_date: null as Date | null,
    })


    function submit(e: { preventDefault: () => void; }) {
        e.preventDefault()
        console.log("Form data:", data)
        console.log(route().has('todos.store'))


        transform((data) => ({
            ...data,
            due_date: data.due_date
                ? data.due_date.toISOString().split('T')[0]
                : null,
        }))

        post(route('todos.store'), {
            forceFormData: true,
        })
    }



    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create" />
            <div className='w-8/12 p-4'>
                <form className='space-y-4 ' onSubmit={submit} encType="multipart/form-data">

                    {Object.keys(errors).length > 0 && (

                        <Alert >
                            <CircleAlert />
                            <AlertTitle>Errors!</AlertTitle>
                            <AlertDescription>
                                <ul>
                                    {Object.entries(errors).map(([key, message]) => (
                                        <li key={key}>{message as string}</li>
                                    ))}
                                </ul>
                            </AlertDescription>
                        </Alert>
                    )}
                    <div className='gap-1.5'>
                        <Label htmlFor="todoName">Todo Name</Label>
                        <Input placeholder='todoname' value={data.name} onChange={e => setData('name', e.target.value)}></Input>
                    </div>
                    <div className='gap-1.5'>
                        <Label htmlFor="description">Description</Label>
                        <Textarea placeholder='description' value={data.description} onChange={e => setData('description', e.target.value)}></Textarea>
                    </div>
                    <div className='gap-1.5'>
                        <Label htmlFor="file">File</Label>
                        <Input placeholder='file' type='file' onChange={(e) => setData('file', e.target.files ? e.target.files[0] : null)} />
                    </div>
                    <Label htmlFor="priority">Priority</Label>
                    <div className="flex gap-6">
                        <label>
                            <input
                                type="radio"
                                name="priority"
                                checked={data.priority === 'high'}
                                onChange={() => setData('priority', 'high')}
                            />
                            High
                        </label>

                        <label>
                            <input
                                type="radio"
                                name="priority"
                                checked={data.priority === 'medium'}
                                onChange={() => setData('priority', 'medium')}
                            />
                            Medium
                        </label>

                        <label>
                            <input
                                type="radio"
                                name="priority"
                                checked={data.priority === 'low'}
                                onChange={() => setData('priority', 'low')}
                            />
                            Low
                        </label>
                    </div>

                    <div className='gap-1.5'>
                        <Calendar22 value={data.due_date}
                            onChange={(date) => setData('due_date', date)} />
                    </div>
                    <Button type='submit'>Add</Button>
                </form>
            </div>
        </AppLayout>
    );
}
