
import TicketCabecera from "./ticketCabecera";
import TicketDetalle from "./ticketDetalle";

export default class Ticket {
    constructor() {
        this.ticketCabecera = new TicketCabecera();
        this.ticketDetalles = [];
    }
}

/* Ejemplo de uso
const comanda = new Comanda();
comanda.ticketCabecera.sucursal = 'HOTEL TRÓPICO INN';
comanda.ticketCabecera.mesa = 1;
comanda.ticketCabecera.id = 123;
comanda.ticketCabecera.caja = 'RECEPCIÓN';
comanda.ticketCabecera.username = 'ADMIN';
comanda.ticketCabecera.tipo_ticket = 'COMPROBANTE DE PRE-FACTURACIÓN';
comanda.ticketCabecera.cliente = 'TURISTICAS DE ORIENTE S.A. DE S.V.';
comanda.ticketCabecera.created_at = '2024-01-11 12:30:00';

const detalle1 = new TicketDetalle();
detalle1.Cantidad = 2;
detalle1.Precio = 1.45;
detalle1.descripcion = "PUPUSAS UNIT."

const detalle2 = new TicketDetalle();
detalle1.Cantidad = 1;
detalle1.Precio = 1.75;
detalle1.descripcion = "COCA-COLA"

comanda.ticketDetalles.push(detalle1, detalle2);

*/
