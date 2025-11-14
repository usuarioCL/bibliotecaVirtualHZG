# Seeders - Biblioteca Virtual HZG

Este directorio contiene los seeders para poblar la base de datos con datos de prueba.

## Contenido

### Seeders de CodeIgniter 4

- **DatabaseSeeder.php** - Seeder principal que ejecuta todos los demás
- **TipoRecursoSeeder.php** - Tipos de recursos (físico/digital)
- **CategoriaSeeder.php** - Categorías y subcategorías
- **EditorialSeeder.php** - Editoriales
- **AutorSeeder.php** - Autores
- **PersonaSeeder.php** - Personas del sistema
- **UsuarioSeeder.php** - Usuarios con diferentes roles
- **GrupoSeeder.php** - Grupos/clases académicas
- **RecursoSeeder.php** - Libros físicos y digitales
- **SancionSeeder.php** - Tipos de sanciones y sanciones de prueba

### Script SQL Alternativo

- **datos_prueba.sql** - Script SQL directo con todos los datos

## Uso

### Opción 1: Usando Seeders de CodeIgniter

```bash
# Ejecutar todos los seeders
php spark db:seed DatabaseSeeder

# Ejecutar un seeder específico
php spark db:seed RecursoSeeder
```

## Usuarios Creados

| admin | admin123 | Administrador | admin@biblioteca.com |
| docente1 | docente123 | Docente | docente1@biblioteca.com |
| docente2 | docente123 | Docente | docente2@biblioteca.com |
| estudiante1 | estudiante123 | Estudiante | estudiante1@biblioteca.com |
| estudiante2 | estudiante123 | Estudiante | estudiante2@biblioteca.com |
| estudiante3 | estudiante123 | Estudiante | estudiante3@biblioteca.com |
| estudiante4 | estudiante123 | Estudiante | estudiante4@biblioteca.com |
