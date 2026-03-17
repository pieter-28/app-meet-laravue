import { User } from '@/types';

export interface Place {
    id: number;
    code: string;
    name: string;
    address: string;
    description: string;
    created_at: string;
    updated_at: string;
}

export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface Paginated<T> {
    data: T[];
    links: PaginationLink[];
    last_page: number;
    current_page: number;
    per_page: number;
    total: number;
}

export interface GrupActivity {
    id: number;
    code: string;
    name: string;
    description: string;
    created_at: string;
    updated_at: string;
}

export interface Activity {
    id: number;
    code: string;
    name: string;
    description: string;
    created_at: string;
    updated_at: string;
}

export interface Pic {
    id: number;
    code: string;
    name: string;
    status: 'active' | 'inactive';
    email?: string;
    phone?: string;
    archievement?: string;
    description?: string;
    created_at: string;
    updated_at: string;
    user?: User;
}
