# Imperial Net - Sitio web

Sitio institucional de Imperial Net, publicado en [imperial-net.com](https://imperial-net.com). Presenta los servicios de desarrollo de software a medida para PyMEs, los productos propios, los clientes y un blog, y recibe consultas por un formulario de contacto.

## Stack

- [Astro 5](https://astro.build), generación de sitio estático
- Bootstrap 5 y Bootstrap Icons
- Sass para la personalización de Bootstrap
- `@astrojs/sitemap` para generar el sitemap
- PHP con PHPMailer para el formulario de contacto

## Páginas

| Ruta | Contenido |
|---|---|
| `/` | Página principal |
| `/productos` | Detalle de los productos propios: QRest, Inventory Desktop y Ficus |
| `/blog` | Listado de artículos |
| `/blog/[artículo]` | Siete artículos sobre digitalización, automatización, inventario, atención al cliente, métricas y software a medida |

La página principal se arma con estas secciones, en orden: hero, cinta de logos de clientes, soluciones, nosotros, testimonios, blog, preguntas frecuentes, contacto y llamado a la acción. El botón flotante de WhatsApp está en todas las páginas.

## Estructura

```
imperial-net-website/
├── public/                 favicons, manifest, robots.txt e imágenes
│   └── img/                logos, clientes, productos y blog
├── src/
│   ├── components/         una sección del sitio por componente
│   ├── layouts/
│   │   └── BaseLayout.astro    meta tags, Open Graph y carga de estilos y scripts
│   ├── pages/              rutas del sitio
│   ├── styles/             un archivo CSS por componente, más base y global
│   ├── services/           backend PHP del formulario de contacto
│   └── config.ts           número de WhatsApp
├── .htaccess               configuración de Apache para el hosting
└── astro.config.mjs
```

## Desarrollo

Requisitos: Node.js 18 o superior.

```bash
npm install
npm run dev
```

El sitio queda en `http://localhost:4321`.

| Comando | Acción |
|---|---|
| `npm run dev` | Servidor de desarrollo |
| `npm run build` | Genera el sitio en `dist/` |
| `npm run preview` | Sirve el build localmente |

El número de WhatsApp se toma de la variable `PUBLIC_WHATSAPP_PHONE`. Si no está definida, se usa el valor de `src/config.ts`.

## Formulario de contacto

El formulario envía la consulta por `fetch` a `/services/api/_send-email.php`. El backend en PHP:

- Valida los campos obligatorios (nombre, apellido, email y mensaje) y el formato del email y del teléfono.
- Admite un archivo adjunto (PDF o imagen, hasta 30 MB), aunque el formulario actual no ofrece ese campo.
- Envía la consulta a Imperial Net y un email de confirmación a quien escribió, con las plantillas de `src/services/email/templates/`.
- Descarta el spam sin avisarle al bot: rechaza los envíos que no vienen de `imperial-net.com` y los que completan un campo trampa (honeypot).

### Variables de entorno

| Variable | Descripción |
|---|---|
| `SMTP_HOST` | Servidor SMTP (por defecto `smtp.hostinger.com`) |
| `SMTP_PORT` | Puerto (por defecto `465`) |
| `SMTP_SECURE` | `true` para SSL (465), `false` para TLS (587) |
| `SMTP_USER` | Cuenta que envía los emails |
| `SMTP_PASS` | Contraseña de esa cuenta |
| `RECIPIENT_EMAIL` | Dirección que recibe las consultas (por defecto, `SMTP_USER`) |

En el servidor, estas variables se definen en un archivo `env-config.php` fuera de la carpeta pública. En desarrollo también se pueden cargar desde un `.env`. Ninguno de los dos archivos se versiona.

## Publicación

El sitio está alojado en un hosting con Apache. La estructura en el servidor es:

```
public_html/
├── .htaccess        el de la raíz de este repositorio
├── dist/            la salida de npm run build, completa
├── services/        copia de src/services
└── env-config.php   credenciales SMTP
```

El `.htaccess`:

- Fuerza HTTPS.
- Sirve el sitio desde `dist/` sin que la carpeta aparezca en la URL, así que basta con reemplazar `dist/` en cada publicación.
- Deja `/services/` fuera de esa reescritura para que el PHP responda.
- Bloquea el acceso a archivos sensibles y ocultos y el listado de directorios.
- Agrega cabeceras de seguridad, caché de recursos estáticos y compresión gzip.

## Licencia

Código propietario de Imperial Net. Todos los derechos reservados.
