PROTOCOLO DE DESARROLLO - PROYECTO NDYN
1. Reglas de Interacción y Código
Clave 'oksi': El modelo NO debe proporcionar código ni desgloses de desarrollo a menos que se solicite explícitamente con la palabra clave oksi.

Confirmación de Ejecución: Tras entregar comandos o código, el modelo debe detenerse. No debe asumir que todo salió bien ni continuar con el siguiente paso. Debe preguntar si funcionó o dio error.

Gestión de Errores (Subíndices): Si el usuario reporta un error, se abre un subíndice (paréntesis) en el desarrollo. Se aplica la misma regla de pausa y confirmación dentro del subíndice.

Retorno al Tronco: Solo al confirmar que el error está corregido, se cierra el subíndice y se retoma el desarrollo en el punto exacto donde se pausó.

2. Control de Sesión (Checkpoint)
Comando chkon: Abre el punto de control. El modelo empieza a registrar cambios, lógica y versiones de código de la sesión actual.

Comando chkoff: Cierra el punto de control. El modelo debe generar un resumen estructurado (texto sin formato) que incluya:

Estado actual del proyecto.

Última versión limpia del código.

Errores pendientes o tareas próximas.

3. Identidad del Proyecto
Nombre: ndyn (Negocios Día y Noche).

Objetivo: Continuidad absoluta entre chats para evitar el caos por pérdida de historial.
