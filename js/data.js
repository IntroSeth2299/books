// Data management module for Book Author Management System
const AppData = {
    // Initialize default data if none exists
    init: function() {
        if (!localStorage.getItem('books')) {
            const defaultBooks = [
                { id: 1, title: "The Great Gatsby", page: 180, publish_year: 1925, category: "Fiction" },
                { id: 2, title: "1984", page: 328, publish_year: 1949, category: "Dystopian" },
                { id: 3, title: "To Kill a Mockingbird", page: 281, publish_year: 1960, category: "Classic" }
            ];
            localStorage.setItem('books', JSON.stringify(defaultBooks));
        }
        
        if (!localStorage.getItem('authors')) {
            const defaultAuthors = [
                { id: 1, name: "F. Scott Fitzgerald", email: "fitzgerald@example.com", address: "St. Paul, Minnesota" },
                { id: 2, name: "George Orwell", email: "orwell@example.com", address: "Motihari, India" },
                { id: 3, name: "Harper Lee", email: "lee@example.com", address: "Monroeville, Alabama" }
            ];
            localStorage.setItem('authors', JSON.stringify(defaultAuthors));
        }
        
        if (!localStorage.getItem('assignments')) {
            const defaultAssignments = [
                { book_id: 1, author_id: 1 },
                { book_id: 2, author_id: 2 }
            ];
            localStorage.setItem('assignments', JSON.stringify(defaultAssignments));
        }
        
        if (!localStorage.getItem('nextIds')) {
            localStorage.setItem('nextIds', JSON.stringify({ book: 4, author: 4 }));
        }
    },
    
    // Books functions
    getBooks: function() {
        return JSON.parse(localStorage.getItem('books')) || [];
    },
    
    getBook: function(id) {
        const books = this.getBooks();
        return books.find(b => b.id == id);
    },
    
    addBook: function(book) {
        const books = this.getBooks();
        const nextIds = JSON.parse(localStorage.getItem('nextIds'));
        book.id = nextIds.book;
        books.push(book);
        localStorage.setItem('books', JSON.stringify(books));
        nextIds.book++;
        localStorage.setItem('nextIds', JSON.stringify(nextIds));
        return book.id;
    },
    
    updateBook: function(id, updatedBook) {
        const books = this.getBooks();
        const index = books.findIndex(b => b.id == id);
        if (index !== -1) {
            updatedBook.id = parseInt(id);
            books[index] = updatedBook;
            localStorage.setItem('books', JSON.stringify(books));
            return true;
        }
        return false;
    },
    
    deleteBook: function(id) {
        let books = this.getBooks();
        books = books.filter(b => b.id != id);
        localStorage.setItem('books', JSON.stringify(books));
        
        // Also remove assignments for this book
        let assignments = this.getAssignments();
        assignments = assignments.filter(a => a.book_id != id);
        localStorage.setItem('assignments', JSON.stringify(assignments));
        return true;
    },
    
    // Authors functions
    getAuthors: function() {
        return JSON.parse(localStorage.getItem('authors')) || [];
    },
    
    getAuthor: function(id) {
        const authors = this.getAuthors();
        return authors.find(a => a.id == id);
    },
    
    addAuthor: function(author) {
        const authors = this.getAuthors();
        const nextIds = JSON.parse(localStorage.getItem('nextIds'));
        author.id = nextIds.author;
        authors.push(author);
        localStorage.setItem('authors', JSON.stringify(authors));
        nextIds.author++;
        localStorage.setItem('nextIds', JSON.stringify(nextIds));
        return author.id;
    },
    
    updateAuthor: function(id, updatedAuthor) {
        const authors = this.getAuthors();
        const index = authors.findIndex(a => a.id == id);
        if (index !== -1) {
            updatedAuthor.id = parseInt(id);
            authors[index] = updatedAuthor;
            localStorage.setItem('authors', JSON.stringify(authors));
            return true;
        }
        return false;
    },
    
    deleteAuthor: function(id) {
        let authors = this.getAuthors();
        authors = authors.filter(a => a.id != id);
        localStorage.setItem('authors', JSON.stringify(authors));
        
        // Also remove assignments for this author
        let assignments = this.getAssignments();
        assignments = assignments.filter(a => a.author_id != id);
        localStorage.setItem('assignments', JSON.stringify(assignments));
        return true;
    },
    
    // Assignments functions
    getAssignments: function() {
        return JSON.parse(localStorage.getItem('assignments')) || [];
    },
    
    getAssignmentsWithDetails: function() {
        const assignments = this.getAssignments();
        const books = this.getBooks();
        const authors = this.getAuthors();
        
        return assignments.map(a => {
            const book = books.find(b => b.id == a.book_id);
            const author = authors.find(au => au.id == a.author_id);
            return {
                book_id: a.book_id,
                author_id: a.author_id,
                book_title: book ? book.title : 'Unknown',
                author_name: author ? author.name : 'Unknown'
            };
        });
    },
    
    addAssignment: function(book_id, author_id) {
        const assignments = this.getAssignments();
        
        // Check if already exists
        const exists = assignments.some(a => a.book_id == book_id && a.author_id == author_id);
        if (!exists) {
            assignments.push({ book_id: parseInt(book_id), author_id: parseInt(author_id) });
            localStorage.setItem('assignments', JSON.stringify(assignments));
            return true;
        }
        return false;
    },
    
    removeAssignment: function(book_id, author_id) {
        let assignments = this.getAssignments();
        assignments = assignments.filter(a => !(a.book_id == book_id && a.author_id == author_id));
        localStorage.setItem('assignments', JSON.stringify(assignments));
        return true;
    },
    
    // Get authors for a specific book
    getBookAuthors: function(book_id) {
        const assignments = this.getAssignments();
        const authors = this.getAuthors();
        const authorIds = assignments.filter(a => a.book_id == book_id).map(a => a.author_id);
        return authors.filter(a => authorIds.includes(a.id));
    },
    
    // Reset all data to default
    resetData: function() {
        localStorage.removeItem('books');
        localStorage.removeItem('authors');
        localStorage.removeItem('assignments');
        localStorage.removeItem('nextIds');
        this.init();
        window.location.reload();
    }
};

// Initialize data when script loads
AppData.init();