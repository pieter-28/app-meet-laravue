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
