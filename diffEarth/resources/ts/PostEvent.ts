export interface Post {
    id: number;
    title: string;
    content: string;
    created_at: string;
    image: string;
    user: {
        name: string;
    };
}

export interface NewPostCreatedEvent {
    post: Post;
}
