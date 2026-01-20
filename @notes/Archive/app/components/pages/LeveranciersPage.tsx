import { useState } from 'react';
import { Plus, Edit, Building2, Calendar, Mail, Phone } from 'lucide-react';
import { Button } from '@/app/components/ui/button';
import { Input } from '@/app/components/ui/input';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/app/components/ui/table';
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/app/components/ui/card';
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/app/components/ui/dialog';
import { Label } from '@/app/components/ui/label';
import { Badge } from '@/app/components/ui/badge';

// Mock data
const initialSuppliers = [
  {
    id: 1,
    companyName: 'Albert Heijn Maaskantje',
    address: 'Hoofdstraat 45, 5251 AA Maaskantje',
    contactPerson: 'Jan van der Berg',
    email: 'jan.vandenberg@ah.nl',
    phone: '0413-234567',
    nextDelivery: '2026-01-20T10:00',
  },
  {
    id: 2,
    companyName: 'Jumbo Supermarkten',
    address: 'Marktplein 12, 5251 BB Maaskantje',
    contactPerson: 'Maria Jansen',
    email: 'm.jansen@jumbo.com',
    phone: '0413-345678',
    nextDelivery: '2026-01-18T14:30',
  },
  {
    id: 3,
    companyName: 'Boerderij van Pelt',
    address: 'Boerenweg 8, 5251 CC Maaskantje',
    contactPerson: 'Piet van Pelt',
    email: 'p.vanpelt@boerderij.nl',
    phone: '0413-456789',
    nextDelivery: '2026-01-17T09:00',
  },
  {
    id: 4,
    companyName: 'Groothandel Smaak',
    address: 'Industrieweg 23, 5251 DD Maaskantje',
    contactPerson: 'Linda Smit',
    email: 'info@smaak.nl',
    phone: '0413-567890',
    nextDelivery: '2026-01-22T11:00',
  },
];

function formatDateTime(dateString: string) {
  const date = new Date(dateString);
  return {
    date: date.toLocaleDateString('nl-NL', { weekday: 'short', day: 'numeric', month: 'short' }),
    time: date.toLocaleTimeString('nl-NL', { hour: '2-digit', minute: '2-digit' }),
  };
}

function getDeliveryStatus(dateString: string) {
  const now = new Date();
  const deliveryDate = new Date(dateString);
  const diffHours = (deliveryDate.getTime() - now.getTime()) / (1000 * 60 * 60);

  if (diffHours < 24) return { variant: 'default' as const, label: 'Binnenkort' };
  if (diffHours < 72) return { variant: 'secondary' as const, label: 'Deze week' };
  return { variant: 'outline' as const, label: 'Gepland' };
}

export default function LeveranciersPage() {
  const [suppliers] = useState(initialSuppliers);
  const [isAddDialogOpen, setIsAddDialogOpen] = useState(false);

  return (
    <div className="space-y-6">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl sm:text-3xl font-bold text-gray-900">Leveranciers</h1>
          <p className="text-sm sm:text-base text-gray-600 mt-1">Beheer leveranciers en leveringsschema's</p>
        </div>
        <Dialog open={isAddDialogOpen} onOpenChange={setIsAddDialogOpen}>
          <DialogTrigger asChild>
            <Button className="gap-2 w-full sm:w-auto">
              <Plus className="h-4 w-4" />
              Nieuwe Leverancier
            </Button>
          </DialogTrigger>
          <DialogContent className="w-[95vw] max-w-2xl max-h-[90vh] overflow-y-auto">
            <DialogHeader>
              <DialogTitle>Nieuwe Leverancier Toevoegen</DialogTitle>
            </DialogHeader>
            <div className="grid gap-4 py-4">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div className="grid gap-2">
                  <Label htmlFor="company">Bedrijfsnaam</Label>
                  <Input id="company" placeholder="Bijv. Albert Heijn" />
                </div>
                <div className="grid gap-2">
                  <Label htmlFor="contactPerson">Contactpersoon</Label>
                  <Input id="contactPerson" placeholder="Naam contactpersoon" />
                </div>
              </div>
              <div className="grid gap-2">
                <Label htmlFor="address">Adres</Label>
                <Input id="address" placeholder="Straat, nummer, postcode, plaats" />
              </div>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div className="grid gap-2">
                  <Label htmlFor="email">E-mailadres</Label>
                  <Input id="email" type="email" placeholder="contact@leverancier.nl" />
                </div>
                <div className="grid gap-2">
                  <Label htmlFor="phone">Telefoonnummer</Label>
                  <Input id="phone" placeholder="0413-123456" />
                </div>
              </div>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div className="grid gap-2">
                  <Label htmlFor="deliveryDate">Eerstvolgende Levering - Datum</Label>
                  <Input id="deliveryDate" type="date" />
                </div>
                <div className="grid gap-2">
                  <Label htmlFor="deliveryTime">Tijd</Label>
                  <Input id="deliveryTime" type="time" />
                </div>
              </div>
            </div>
            <div className="flex flex-col-reverse sm:flex-row justify-end gap-2">
              <Button variant="outline" onClick={() => setIsAddDialogOpen(false)} className="w-full sm:w-auto">
                Annuleren
              </Button>
              <Button onClick={() => setIsAddDialogOpen(false)} className="w-full sm:w-auto">Opslaan</Button>
            </div>
          </DialogContent>
        </Dialog>
      </div>

      <div className="grid gap-4 sm:gap-6 grid-cols-1 lg:grid-cols-2">
        {suppliers.map((supplier) => {
          const { date, time } = formatDateTime(supplier.nextDelivery);
          const status = getDeliveryStatus(supplier.nextDelivery);

          return (
            <Card key={supplier.id} className="hover:shadow-lg transition-shadow">
              <CardHeader>
                <div className="flex items-start justify-between gap-2">
                  <div className="flex items-start gap-3 flex-1 min-w-0">
                    <div className="p-2 bg-green-100 rounded-lg flex-shrink-0">
                      <Building2 className="h-5 w-5 sm:h-6 sm:w-6 text-green-600" />
                    </div>
                    <div className="min-w-0 flex-1">
                      <CardTitle className="text-lg sm:text-xl truncate">{supplier.companyName}</CardTitle>
                      <CardDescription className="mt-1 text-xs sm:text-sm">{supplier.address}</CardDescription>
                    </div>
                  </div>
                  <Button variant="ghost" size="sm" className="flex-shrink-0">
                    <Edit className="h-4 w-4" />
                  </Button>
                </div>
              </CardHeader>
              <CardContent className="space-y-4">
                <div className="grid gap-3">
                  <div className="flex items-center gap-2 text-sm">
                    <Mail className="h-4 w-4 text-gray-400 flex-shrink-0" />
                    <span className="text-gray-600">Contactpersoon:</span>
                    <span className="font-medium truncate">{supplier.contactPerson}</span>
                  </div>
                  <div className="flex items-center gap-2 text-sm min-w-0">
                    <Mail className="h-4 w-4 text-gray-400 flex-shrink-0" />
                    <a href={`mailto:${supplier.email}`} className="text-blue-600 hover:underline truncate">
                      {supplier.email}
                    </a>
                  </div>
                  <div className="flex items-center gap-2 text-sm">
                    <Phone className="h-4 w-4 text-gray-400 flex-shrink-0" />
                    <a href={`tel:${supplier.phone}`} className="text-blue-600 hover:underline">
                      {supplier.phone}
                    </a>
                  </div>
                </div>

                <div className="pt-4 border-t">
                  <div className="flex items-center justify-between gap-2 flex-wrap">
                    <div className="flex items-center gap-2">
                      <Calendar className="h-4 w-4 text-gray-400 flex-shrink-0" />
                      <span className="text-sm text-gray-600">Eerstvolgende levering:</span>
                    </div>
                    <Badge variant={status.variant}>{status.label}</Badge>
                  </div>
                  <div className="mt-2 text-right">
                    <span className="text-sm font-medium">{date}</span>
                    <span className="text-sm text-gray-500 ml-2">{time}</span>
                  </div>
                </div>
              </CardContent>
            </Card>
          );
        })}
      </div>

      <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <h2 className="text-base sm:text-lg font-semibold mb-4">Leveringsoverzicht</h2>
        
        {/* Desktop Table View */}
        <div className="hidden sm:block overflow-x-auto">
          <Table>
            <TableHeader>
              <TableRow className="bg-gray-50">
                <TableHead>Leverancier</TableHead>
                <TableHead>Contactpersoon</TableHead>
                <TableHead>Volgende Levering</TableHead>
                <TableHead>Status</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {suppliers
                .sort((a, b) => new Date(a.nextDelivery).getTime() - new Date(b.nextDelivery).getTime())
                .map((supplier) => {
                  const { date, time } = formatDateTime(supplier.nextDelivery);
                  const status = getDeliveryStatus(supplier.nextDelivery);

                  return (
                    <TableRow key={supplier.id}>
                      <TableCell className="font-medium">{supplier.companyName}</TableCell>
                      <TableCell>{supplier.contactPerson}</TableCell>
                      <TableCell>
                        <div className="flex flex-col">
                          <span className="text-sm">{date}</span>
                          <span className="text-xs text-gray-500">{time}</span>
                        </div>
                      </TableCell>
                      <TableCell>
                        <Badge variant={status.variant}>{status.label}</Badge>
                      </TableCell>
                    </TableRow>
                  );
                })}
            </TableBody>
          </Table>
        </div>

        {/* Mobile List View */}
        <div className="sm:hidden space-y-3">
          {suppliers
            .sort((a, b) => new Date(a.nextDelivery).getTime() - new Date(b.nextDelivery).getTime())
            .map((supplier) => {
              const { date, time } = formatDateTime(supplier.nextDelivery);
              const status = getDeliveryStatus(supplier.nextDelivery);

              return (
                <div key={supplier.id} className="border rounded-lg p-3 space-y-2">
                  <div className="flex items-start justify-between gap-2">
                    <div>
                      <h3 className="font-medium text-sm">{supplier.companyName}</h3>
                      <p className="text-xs text-gray-600 mt-1">{supplier.contactPerson}</p>
                    </div>
                    <Badge variant={status.variant} className="text-xs">{status.label}</Badge>
                  </div>
                  <div className="text-xs text-gray-600">
                    <span>{date}</span>
                    <span className="mx-1">•</span>
                    <span>{time}</span>
                  </div>
                </div>
              );
            })}
        </div>
      </div>
    </div>
  );
}