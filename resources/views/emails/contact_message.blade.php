<x-mail::message>
# Nuevo mensaje de contacto

Has recibido un nuevo mensaje a través del formulario de contacto de ProReserve.

**Nombre:** {{ $data['name'] }}  
**Email:** {{ $data['email'] }}  
**Asunto:** {{ $data['subject'] }}

**Mensaje:**  
{{ $data['message'] }}

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
