import { useState } from 'react';
import { Plus, Minus, PackagePlus, Users, CheckCircle2, Trash2, Eye, Edit, X, Check, Tag } from 'lucide-react';
import { Button } from '@/app/components/ui/button';
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
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/app/components/ui/select';
import { Label } from '@/app/components/ui/label';
import { Badge } from '@/app/components/ui/badge';
import { Separator } from '@/app/components/ui/separator';
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from '@/app/components/ui/dialog';
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '@/app/components/ui/alert-dialog';

// Mock data
const customers = [
  {
    id: 1,
    name: 'Familie Jansen',
    address: 'Dorpsstraat 12, Maaskantje',
    composition: { adults: 2, children: 2, babies: 0 },
    preferences: ['Geen varkensvlees'],
  },
  {
    id: 2,
    name: 'Mevrouw Bakker',
    address: 'Kerkweg 5, Maaskantje',
    composition: { adults: 1, children: 0, babies: 0 },
    preferences: ['Vegetarisch'],
  },
  {
    id: 3,
    name: 'Familie de Vries',
    address: 'Schoollaan 23, Maaskantje',
    composition: { adults: 2, children: 1, babies: 1 },
    preferences: ['Allergisch voor lactose'],
  },
  {
    id: 4,
    name: 'De heer Vermeulen',
    address: 'Molenstraat 8, Maaskantje',
    composition: { adults: 1, children: 0, babies: 0 },
    preferences: ['Veganistisch'],
  },
];

const availableProducts = [
  { id: 1, name: 'Melk Vol 1L', category: 'Zuivel', stock: 45 },
  { id: 2, name: 'Volkoren Brood', category: 'Bakkerij', stock: 28 },
  { id: 3, name: 'Aardappelen 1kg', category: 'Groente', stock: 62 },
  { id: 4, name: 'Kip Filet 500g', category: 'Vlees', stock: 15 },
  { id: 5, name: 'Pasta Penne 500g', category: 'Pasta', stock: 88 },
  { id: 6, name: 'Appelsap 1L', category: 'Dranken', stock: 34 },
  { id: 7, name: 'Tomatensoep blik', category: 'Soepen', stock: 52 },
  { id: 8, name: 'Koekjes Choco', category: 'Snoep', stock: 19 },
  { id: 9, name: 'Babyvoeding 6m+', category: 'Baby', stock: 23 },
  { id: 10, name: 'Rijst 1kg', category: 'Pasta', stock: 41 },
];

interface PackageItem {
  productId: number;
  quantity: number;
}

interface SavedPackage {
  id: number;
  customerId: number;
  customerName: string;
  assemblyDate: Date;
  distributionDate: Date;
  items: PackageItem[];
  totalItems: number;
  status: 'Samengesteld' | 'Uitgereikt';
}

// Mock saved packages
const initialSavedPackages: SavedPackage[] = [
  {
    id: 1,
    customerId: 1,
    customerName: 'Familie Jansen',
    assemblyDate: new Date('2026-01-15'),
    distributionDate: new Date('2026-01-17'),
    items: [
      { productId: 1, quantity: 2 },
      { productId: 2, quantity: 2 },
      { productId: 3, quantity: 1 },
      { productId: 5, quantity: 1 },
      { productId: 6, quantity: 2 },
    ],
    totalItems: 8,
    status: 'Uitgereikt',
  },
  {
    id: 2,
    customerId: 2,
    customerName: 'Mevrouw Bakker',
    assemblyDate: new Date('2026-01-16'),
    distributionDate: new Date('2026-01-17'),
    items: [
      { productId: 2, quantity: 1 },
      { productId: 3, quantity: 1 },
      { productId: 5, quantity: 1 },
      { productId: 7, quantity: 2 },
    ],
    totalItems: 5,
    status: 'Samengesteld',
  },
  {
    id: 3,
    customerId: 3,
    customerName: 'Familie de Vries',
    assemblyDate: new Date('2026-01-16'),
    distributionDate: new Date('2026-01-17'),
    items: [
      { productId: 1, quantity: 2 },
      { productId: 2, quantity: 2 },
      { productId: 3, quantity: 1 },
      { productId: 9, quantity: 3 },
      { productId: 10, quantity: 1 },
    ],
    totalItems: 9,
    status: 'Samengesteld',
  },
  {
    id: 4,
    customerId: 4,
    customerName: 'De heer Vermeulen',
    assemblyDate: new Date('2026-01-16'),
    distributionDate: new Date('2026-01-17'),
    items: [
      { productId: 2, quantity: 1 },
      { productId: 5, quantity: 1 },
      { productId: 7, quantity: 1 },
      { productId: 10, quantity: 1 },
    ],
    totalItems: 4,
    status: 'Samengesteld',
  },
];

export default function VoedselpakketPage() {
  const [selectedCustomer, setSelectedCustomer] = useState<number | null>(null);
  const [packageItems, setPackageItems] = useState<PackageItem[]>([]);
  const [assemblyDate] = useState(new Date());
  const [distributionDate] = useState(new Date(Date.now() + 86400000)); // +1 day
  const [savedPackages, setSavedPackages] = useState<SavedPackage[]>(initialSavedPackages);
  const [isViewDialogOpen, setIsViewDialogOpen] = useState(false);
  const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState(false);
  const [isStickerDialogOpen, setIsStickerDialogOpen] = useState(false);
  const [viewingPackage, setViewingPackage] = useState<SavedPackage | null>(null);
  const [deletingPackage, setDeletingPackage] = useState<SavedPackage | null>(null);
  const [editingPackage, setEditingPackage] = useState<SavedPackage | null>(null);
  const [stickerPackage, setStickerPackage] = useState<SavedPackage | null>(null);

  const customer = customers.find((c) => c.id === selectedCustomer);

  const addProduct = (productId: number) => {
    const existingItem = packageItems.find((item) => item.productId === productId);
    if (existingItem) {
      setPackageItems(
        packageItems.map((item) =>
          item.productId === productId ? { ...item, quantity: item.quantity + 1 } : item
        )
      );
    } else {
      setPackageItems([...packageItems, { productId, quantity: 1 }]);
    }
  };

  const removeProduct = (productId: number) => {
    const existingItem = packageItems.find((item) => item.productId === productId);
    if (existingItem && existingItem.quantity > 1) {
      setPackageItems(
        packageItems.map((item) =>
          item.productId === productId ? { ...item, quantity: item.quantity - 1 } : item
        )
      );
    } else {
      setPackageItems(packageItems.filter((item) => item.productId !== productId));
    }
  };

  const getProductQuantityInPackage = (productId: number) => {
    return packageItems.find((item) => item.productId === productId)?.quantity || 0;
  };

  const totalItems = packageItems.reduce((sum, item) => sum + item.quantity, 0);

  const handleSavePackage = () => {
    if (!selectedCustomer || packageItems.length === 0) return;

    if (editingPackage) {
      // Update existing package
      const updatedPackage: SavedPackage = {
        ...editingPackage,
        customerId: selectedCustomer,
        customerName: customer?.name || '',
        items: packageItems,
        totalItems: totalItems,
      };

      setSavedPackages(
        savedPackages.map((pkg) => (pkg.id === editingPackage.id ? updatedPackage : pkg))
      );
      setEditingPackage(null);
      setPackageItems([]);
      setSelectedCustomer(null);
      alert('Voedselpakket succesvol bijgewerkt!');
    } else {
      // Create new package
      const newPackage: SavedPackage = {
        id: Math.max(...savedPackages.map(p => p.id), 0) + 1,
        customerId: selectedCustomer,
        customerName: customer?.name || '',
        assemblyDate: assemblyDate,
        distributionDate: distributionDate,
        items: packageItems,
        totalItems: totalItems,
        status: 'Samengesteld',
      };

      setSavedPackages([newPackage, ...savedPackages]);
      setPackageItems([]);
      setSelectedCustomer(null);
      alert('Voedselpakket succesvol opgeslagen!');
    }
  };

  const handleViewPackage = (pkg: SavedPackage) => {
    setViewingPackage(pkg);
    setIsViewDialogOpen(true);
  };

  const handleDeleteClick = (pkg: SavedPackage) => {
    setDeletingPackage(pkg);
    setIsDeleteDialogOpen(true);
  };

  const handleConfirmDelete = () => {
    if (deletingPackage) {
      setSavedPackages(savedPackages.filter((pkg) => pkg.id !== deletingPackage.id));
      setIsDeleteDialogOpen(false);
      setDeletingPackage(null);
    }
  };

  const handleEditPackage = (pkg: SavedPackage) => {
    setEditingPackage(pkg);
    setSelectedCustomer(pkg.customerId);
    setPackageItems(pkg.items);
    // Scroll to form
    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
  };

  const handleCancelEdit = () => {
    setEditingPackage(null);
    setPackageItems([]);
    setSelectedCustomer(null);
  };

  const handleMarkAsPickedUp = (pkg: SavedPackage) => {
    setSavedPackages(
      savedPackages.map((p) =>
        p.id === pkg.id ? { ...p, status: 'Uitgereikt' } : p
      )
    );
    alert(`Pakket #${pkg.id} is gemarkeerd als uitgereikt!`);
  };

  const handleStickerClick = (pkg: SavedPackage) => {
    setStickerPackage(pkg);
    setIsStickerDialogOpen(true);
  };

  return (
    <div className="space-y-8">
      {/* Header */}
      <div>
        <h1 className="text-2xl sm:text-3xl font-bold text-gray-900">Voedselpakketten</h1>
        <p className="text-sm sm:text-base text-gray-600 mt-1">Beheer en stel voedselpakketten samen</p>
      </div>

      {/* Pakket Overzicht */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <PackagePlus className="h-5 w-5" />
            Pakket Overzicht
          </CardTitle>
          <CardDescription>Alle samengestelde voedselpakketten</CardDescription>
        </CardHeader>
        <CardContent>
          {/* Desktop Table */}
          <div className="hidden md:block border rounded-lg overflow-hidden">
            <Table>
              <TableHeader>
                <TableRow className="bg-gray-50">
                  <TableHead>Pakket #</TableHead>
                  <TableHead>Klant</TableHead>
                  <TableHead>Samengesteld</TableHead>
                  <TableHead>Uitgifte</TableHead>
                  <TableHead className="text-center">Items</TableHead>
                  <TableHead className="text-center">Status</TableHead>
                  <TableHead className="text-right">Acties</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {savedPackages.length === 0 ? (
                  <TableRow>
                    <TableCell colSpan={7} className="text-center text-gray-500 py-8">
                      Geen pakketten gevonden
                    </TableCell>
                  </TableRow>
                ) : (
                  savedPackages.map((pkg) => (
                    <TableRow key={pkg.id}>
                      <TableCell className="font-mono text-sm">#{pkg.id}</TableCell>
                      <TableCell className="font-medium">{pkg.customerName}</TableCell>
                      <TableCell className="text-sm text-gray-600">
                        {pkg.assemblyDate.toLocaleDateString('nl-NL')}
                      </TableCell>
                      <TableCell className="text-sm text-gray-600">
                        {pkg.distributionDate.toLocaleDateString('nl-NL')}
                      </TableCell>
                      <TableCell className="text-center">
                        <Badge variant="secondary">{pkg.totalItems}</Badge>
                      </TableCell>
                      <TableCell className="text-center">
                        <Badge variant={pkg.status === 'Uitgereikt' ? 'default' : 'outline'}>
                          {pkg.status}
                        </Badge>
                      </TableCell>
                      <TableCell className="text-right">
                        <div className="flex items-center justify-end gap-1">
                          <Button
                            variant="ghost"
                            size="sm"
                            onClick={() => handleViewPackage(pkg)}
                          >
                            <Eye className="h-4 w-4" />
                          </Button>
                          {pkg.status === 'Samengesteld' && (
                            <Button
                              variant="ghost"
                              size="sm"
                              onClick={() => handleMarkAsPickedUp(pkg)}
                              title="Markeer als uitgereikt"
                            >
                              <Check className="h-4 w-4 text-green-600" />
                            </Button>
                          )}
                          <Button
                            variant="ghost"
                            size="sm"
                            onClick={() => handleEditPackage(pkg)}
                          >
                            <Edit className="h-4 w-4 text-blue-600" />
                          </Button>
                          <Button
                            variant="ghost"
                            size="sm"
                            onClick={() => handleDeleteClick(pkg)}
                          >
                            <Trash2 className="h-4 w-4 text-red-600" />
                          </Button>
                          <Button
                            variant="ghost"
                            size="sm"
                            onClick={() => handleStickerClick(pkg)}
                          >
                            <Tag className="h-4 w-4 text-blue-600" />
                          </Button>
                        </div>
                      </TableCell>
                    </TableRow>
                  ))
                )}
              </TableBody>
            </Table>
          </div>

          {/* Mobile Cards */}
          <div className="md:hidden space-y-3">
            {savedPackages.length === 0 ? (
              <div className="text-center text-gray-500 py-8">
                Geen pakketten gevonden
              </div>
            ) : (
              savedPackages.map((pkg) => (
                <div key={pkg.id} className="border rounded-lg p-4 space-y-3">
                  <div className="flex items-start justify-between">
                    <div className="flex-1">
                      <div className="flex items-center gap-2">
                        <span className="font-mono text-sm text-gray-500">#{pkg.id}</span>
                        <Badge variant={pkg.status === 'Uitgereikt' ? 'default' : 'outline'} className="text-xs">
                          {pkg.status}
                        </Badge>
                      </div>
                      <h3 className="font-medium text-gray-900 mt-1">{pkg.customerName}</h3>
                      <p className="text-xs text-gray-600 mt-1">
                        Samengesteld: {pkg.assemblyDate.toLocaleDateString('nl-NL')}
                      </p>
                      <p className="text-xs text-gray-600">
                        Uitgifte: {pkg.distributionDate.toLocaleDateString('nl-NL')}
                      </p>
                    </div>
                    <Badge variant="secondary" className="flex-shrink-0">{pkg.totalItems} items</Badge>
                  </div>
                  
                  {/* Action Buttons - 2 rows */}
                  <div className="space-y-2">
                    <div className="grid grid-cols-2 gap-2">
                      <Button
                        variant="outline"
                        size="sm"
                        className="gap-2"
                        onClick={() => handleViewPackage(pkg)}
                      >
                        <Eye className="h-4 w-4" />
                        Bekijk
                      </Button>
                      <Button
                        variant="outline"
                        size="sm"
                        className="gap-2"
                        onClick={() => handleStickerClick(pkg)}
                      >
                        <Tag className="h-4 w-4 text-blue-600" />
                        Sticker
                      </Button>
                    </div>
                    
                    <div className="grid grid-cols-2 gap-2">
                      {pkg.status === 'Samengesteld' ? (
                        <Button
                          variant="outline"
                          size="sm"
                          className="gap-2"
                          onClick={() => handleMarkAsPickedUp(pkg)}
                        >
                          <Check className="h-4 w-4 text-green-600" />
                          Opgehaald
                        </Button>
                      ) : (
                        <div className="flex items-center justify-center text-xs text-gray-500 border border-dashed rounded-md">
                          Al uitgereikt
                        </div>
                      )}
                      <Button
                        variant="outline"
                        size="sm"
                        className="gap-2"
                        onClick={() => handleEditPackage(pkg)}
                      >
                        <Edit className="h-4 w-4 text-blue-600" />
                        Bewerk
                      </Button>
                    </div>
                    
                    <Button
                      variant="outline"
                      size="sm"
                      className="w-full gap-2"
                      onClick={() => handleDeleteClick(pkg)}
                    >
                      <Trash2 className="h-4 w-4 text-red-600" />
                      Verwijder
                    </Button>
                  </div>
                </div>
              ))
            )}
          </div>
        </CardContent>
      </Card>

      {/* Nieuw Pakket Samenstellen */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            {editingPackage ? (
              <>
                <Edit className="h-5 w-5" />
                Pakket #{editingPackage.id} Bewerken
              </>
            ) : (
              <>
                <Plus className="h-5 w-5" />
                Nieuw Pakket Samenstellen
              </>
            )}
          </CardTitle>
          <CardDescription>
            {editingPackage 
              ? `Bewerk het voedselpakket voor ${editingPackage.customerName}` 
              : 'Stel een nieuw voedselpakket samen voor een klant'
            }
          </CardDescription>
        </CardHeader>
        <CardContent className="space-y-6">
          {/* Step 1: Select Customer */}
          <div className="space-y-3">
            <Label className="text-base font-semibold">Stap 1: Selecteer Klant</Label>
            <Select
              value={selectedCustomer?.toString()}
              onValueChange={(value) => setSelectedCustomer(Number(value))}
            >
              <SelectTrigger>
                <SelectValue placeholder="Kies een klant..." />
              </SelectTrigger>
              <SelectContent>
                {customers.map((customer) => (
                  <SelectItem key={customer.id} value={customer.id.toString()}>
                    <div className="flex flex-col">
                      <span className="font-medium">{customer.name}</span>
                      <span className="text-xs text-gray-500">{customer.address}</span>
                    </div>
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>

            {customer && (
              <div className="mt-3 p-4 bg-gray-50 rounded-lg space-y-2">
                <div className="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2 text-sm">
                  <span className="text-gray-600 font-medium sm:font-normal">Gezinssamenstelling:</span>
                  <span className="font-medium">
                    {customer.composition.adults} volwassene(n), {customer.composition.children}{' '}
                    kind(eren), {customer.composition.babies} baby('s)
                  </span>
                </div>
                <div className="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2 text-sm">
                  <span className="text-gray-600 font-medium sm:font-normal">Voorkeuren:</span>
                  <div className="flex gap-1 flex-wrap">
                    {customer.preferences.map((pref, idx) => (
                      <Badge key={idx} variant="outline" className="text-xs">
                        {pref}
                      </Badge>
                    ))}
                  </div>
                </div>
              </div>
            )}
          </div>

          <Separator />

          {/* Step 2: Add Products */}
          <div className="space-y-3">
            <Label className="text-base font-semibold">Stap 2: Voeg Producten Toe</Label>
            
            {!selectedCustomer ? (
              <div className="text-center py-8 text-gray-500 border rounded-lg">
                <Users className="h-12 w-12 mx-auto mb-3 opacity-50" />
                <p className="text-sm">Selecteer eerst een klant om producten toe te voegen</p>
              </div>
            ) : (
              <>
                {/* Desktop Table */}
                <div className="hidden md:block border rounded-lg overflow-hidden">
                  <Table>
                    <TableHeader>
                      <TableRow className="bg-gray-50">
                        <TableHead>Product</TableHead>
                        <TableHead>Categorie</TableHead>
                        <TableHead className="text-center">Voorraad</TableHead>
                        <TableHead className="text-center">Aantal</TableHead>
                        <TableHead className="text-right">Acties</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {availableProducts.map((product) => {
                        const quantityInPackage = getProductQuantityInPackage(product.id);
                        const hasStock = product.stock > 0;

                        return (
                          <TableRow key={product.id}>
                            <TableCell className="font-medium">{product.name}</TableCell>
                            <TableCell className="text-sm text-gray-600">{product.category}</TableCell>
                            <TableCell className="text-center">
                              <Badge
                                variant={
                                  product.stock < 20
                                    ? 'destructive'
                                    : product.stock < 40
                                    ? 'default'
                                    : 'secondary'
                                }
                              >
                                {product.stock}
                              </Badge>
                            </TableCell>
                            <TableCell className="text-center">
                              {quantityInPackage > 0 ? (
                                <span className="font-semibold text-green-600">{quantityInPackage}</span>
                              ) : (
                                <span className="text-gray-400">0</span>
                              )}
                            </TableCell>
                            <TableCell className="text-right">
                              <div className="flex items-center justify-end gap-1">
                                <Button
                                  variant="outline"
                                  size="sm"
                                  onClick={() => removeProduct(product.id)}
                                  disabled={quantityInPackage === 0}
                                >
                                  <Minus className="h-4 w-4" />
                                </Button>
                                <Button
                                  variant="outline"
                                  size="sm"
                                  onClick={() => addProduct(product.id)}
                                  disabled={!hasStock}
                                >
                                  <Plus className="h-4 w-4" />
                                </Button>
                              </div>
                            </TableCell>
                          </TableRow>
                        );
                      })}
                    </TableBody>
                  </Table>
                </div>

                {/* Mobile Cards */}
                <div className="md:hidden space-y-3">
                  {availableProducts.map((product) => {
                    const quantityInPackage = getProductQuantityInPackage(product.id);
                    const hasStock = product.stock > 0;

                    return (
                      <div key={product.id} className="border rounded-lg p-3 space-y-3">
                        <div className="flex items-start justify-between gap-2">
                          <div className="flex-1 min-w-0">
                            <h3 className="font-medium text-sm">{product.name}</h3>
                            <p className="text-xs text-gray-600 mt-1">{product.category}</p>
                          </div>
                          <Badge
                            variant={
                              product.stock < 20
                                ? 'destructive'
                                : product.stock < 40
                                ? 'default'
                                : 'secondary'
                            }
                            className="text-xs flex-shrink-0"
                          >
                            {product.stock}
                          </Badge>
                        </div>
                        <div className="flex items-center justify-between gap-2">
                          <div className="text-sm">
                            <span className="text-gray-600">In pakket: </span>
                            {quantityInPackage > 0 ? (
                              <span className="font-semibold text-green-600">{quantityInPackage}</span>
                            ) : (
                              <span className="text-gray-400">0</span>
                            )}
                          </div>
                          <div className="flex items-center gap-1">
                            <Button
                              variant="outline"
                              size="sm"
                              onClick={() => removeProduct(product.id)}
                              disabled={quantityInPackage === 0}
                            >
                              <Minus className="h-4 w-4" />
                            </Button>
                            <Button
                              variant="outline"
                              size="sm"
                              onClick={() => addProduct(product.id)}
                              disabled={!hasStock}
                            >
                              <Plus className="h-4 w-4" />
                            </Button>
                          </div>
                        </div>
                      </div>
                    );
                  })}
                </div>
              </>
            )}
          </div>

          <Separator />

          {/* Summary and Save */}
          <div className="space-y-4">
            <div className="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
              <span className="font-semibold">Totaal aantal items:</span>
              <Badge variant="secondary" className="text-base">
                {totalItems}
              </Badge>
            </div>

            <Button
              className="w-full gap-2 h-12"
              onClick={handleSavePackage}
              disabled={!selectedCustomer || packageItems.length === 0}
              size="lg"
            >
              <CheckCircle2 className="h-5 w-5" />
              {editingPackage ? 'Pakket Bijwerken' : 'Pakket Opslaan'}
            </Button>

            {editingPackage && (
              <Button
                className="w-full gap-2 h-12"
                onClick={handleCancelEdit}
                variant="outline"
                size="lg"
              >
                <X className="h-5 w-5" />
                Annuleren
              </Button>
            )}
          </div>
        </CardContent>
      </Card>

      {/* View Package Dialog */}
      <Dialog open={isViewDialogOpen} onOpenChange={setIsViewDialogOpen}>
        <DialogContent className="w-[95vw] max-w-2xl max-h-[90vh] overflow-y-auto">
          <DialogHeader>
            <DialogTitle>Pakket Details</DialogTitle>
          </DialogHeader>
          {viewingPackage && (
            <div className="space-y-4">
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <Label className="text-sm text-gray-600">Pakket Nummer</Label>
                  <p className="font-mono font-medium">#{viewingPackage.id}</p>
                </div>
                <div>
                  <Label className="text-sm text-gray-600">Status</Label>
                  <div className="mt-1">
                    <Badge variant={viewingPackage.status === 'Uitgereikt' ? 'default' : 'outline'}>
                      {viewingPackage.status}
                    </Badge>
                  </div>
                </div>
              </div>

              <Separator />

              <div>
                <Label className="text-sm text-gray-600">Klant</Label>
                <p className="font-medium">{viewingPackage.customerName}</p>
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <Label className="text-sm text-gray-600">Samengesteld op</Label>
                  <p className="font-medium">
                    {viewingPackage.assemblyDate.toLocaleDateString('nl-NL')}
                  </p>
                </div>
                <div>
                  <Label className="text-sm text-gray-600">Uitgiftedatum</Label>
                  <p className="font-medium">
                    {viewingPackage.distributionDate.toLocaleDateString('nl-NL')}
                  </p>
                </div>
              </div>

              <Separator />

              <div>
                <Label className="text-sm text-gray-600 mb-3 block">Producten ({viewingPackage.totalItems} items)</Label>
                <div className="border rounded-lg overflow-hidden">
                  <Table>
                    <TableHeader>
                      <TableRow className="bg-gray-50">
                        <TableHead>Product</TableHead>
                        <TableHead className="text-right">Aantal</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {viewingPackage.items.map((item) => {
                        const product = availableProducts.find((p) => p.id === item.productId);
                        return (
                          <TableRow key={item.productId}>
                            <TableCell className="font-medium">{product?.name}</TableCell>
                            <TableCell className="text-right">
                              <Badge variant="secondary">{item.quantity}x</Badge>
                            </TableCell>
                          </TableRow>
                        );
                      })}
                    </TableBody>
                  </Table>
                </div>
              </div>
            </div>
          )}
        </DialogContent>
      </Dialog>

      {/* Delete Confirmation Dialog */}
      <AlertDialog open={isDeleteDialogOpen} onOpenChange={setIsDeleteDialogOpen}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Pakket Verwijderen</AlertDialogTitle>
            <AlertDialogDescription>
              Weet je zeker dat je pakket #{deletingPackage?.id} voor {deletingPackage?.customerName} wilt verwijderen? 
              Deze actie kan niet ongedaan worden gemaakt.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Annuleren</AlertDialogCancel>
            <AlertDialogAction onClick={handleConfirmDelete} className="bg-red-600 hover:bg-red-700">
              Verwijderen
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>

      {/* Sticker Dialog */}
      <Dialog open={isStickerDialogOpen} onOpenChange={setIsStickerDialogOpen}>
        <DialogContent className="w-[95vw] max-w-3xl max-h-[90vh] overflow-y-auto">
          <DialogHeader>
            <DialogTitle className="flex items-center gap-2">
              <Tag className="h-5 w-5" />
              Sticker Informatie
            </DialogTitle>
          </DialogHeader>
          {stickerPackage && (() => {
            const pkgCustomer = customers.find((c) => c.id === stickerPackage.customerId);
            return (
              <div className="space-y-6">
                {/* Sticker Preview - Print Ready */}
                <div className="border-4 border-gray-900 rounded-lg p-6 bg-white space-y-4 print:border-2">
                  {/* Header */}
                  <div className="text-center border-b-2 border-gray-900 pb-3">
                    <h2 className="text-xl font-bold">Voedselbank Maaskantje</h2>
                    <p className="text-sm text-gray-600 mt-1">Voedselpakket</p>
                  </div>

                  {/* Pakket Info */}
                  <div className="grid grid-cols-2 gap-4">
                    <div className="space-y-1">
                      <Label className="text-xs text-gray-600 uppercase">Pakket Nummer</Label>
                      <p className="font-mono text-2xl font-bold">#{stickerPackage.id}</p>
                    </div>
                    <div className="space-y-1">
                      <Label className="text-xs text-gray-600 uppercase">Status</Label>
                      <div className="mt-1">
                        <Badge variant={stickerPackage.status === 'Uitgereikt' ? 'default' : 'outline'} className="text-sm">
                          {stickerPackage.status}
                        </Badge>
                      </div>
                    </div>
                  </div>

                  <Separator />

                  {/* Klant Informatie */}
                  <div className="space-y-3 bg-gray-50 p-4 rounded-lg">
                    <div>
                      <Label className="text-xs text-gray-600 uppercase block mb-1">Klant Naam</Label>
                      <p className="text-lg font-bold">{stickerPackage.customerName}</p>
                    </div>
                    {pkgCustomer && (
                      <>
                        <div>
                          <Label className="text-xs text-gray-600 uppercase block mb-1">Adres</Label>
                          <p className="font-medium">{pkgCustomer.address}</p>
                        </div>
                        <div>
                          <Label className="text-xs text-gray-600 uppercase block mb-1">Gezinssamenstelling</Label>
                          <p className="text-sm font-medium">
                            {pkgCustomer.composition.adults} volw. • {pkgCustomer.composition.children} kind. • {pkgCustomer.composition.babies} baby
                          </p>
                        </div>
                        <div>
                          <Label className="text-xs text-gray-600 uppercase block mb-1">Dieetvoorkeuren</Label>
                          <div className="flex gap-1 flex-wrap">
                            {pkgCustomer.preferences.map((pref, idx) => (
                              <Badge key={idx} variant="outline" className="text-xs">
                                {pref}
                              </Badge>
                            ))}
                          </div>
                        </div>
                      </>
                    )}
                  </div>

                  <Separator />

                  {/* Datums */}
                  <div className="grid grid-cols-2 gap-4">
                    <div className="space-y-1">
                      <Label className="text-xs text-gray-600 uppercase">Samengesteld</Label>
                      <p className="font-bold">
                        {stickerPackage.assemblyDate.toLocaleDateString('nl-NL', {
                          weekday: 'short',
                          year: 'numeric',
                          month: 'short',
                          day: 'numeric',
                        })}
                      </p>
                    </div>
                    <div className="space-y-1">
                      <Label className="text-xs text-gray-600 uppercase">Uitgiftedatum</Label>
                      <p className="font-bold text-green-700">
                        {stickerPackage.distributionDate.toLocaleDateString('nl-NL', {
                          weekday: 'short',
                          year: 'numeric',
                          month: 'short',
                          day: 'numeric',
                        })}
                      </p>
                    </div>
                  </div>

                  <Separator />

                  {/* Producten Overzicht */}
                  <div>
                    <div className="flex items-center justify-between mb-3">
                      <Label className="text-xs text-gray-600 uppercase">Pakket Inhoud</Label>
                      <Badge variant="secondary" className="text-sm">
                        {stickerPackage.totalItems} items
                      </Badge>
                    </div>
                    <div className="border-2 border-gray-900 rounded-lg overflow-hidden">
                      <Table>
                        <TableHeader>
                          <TableRow className="bg-gray-900">
                            <TableHead className="text-white font-bold">Product</TableHead>
                            <TableHead className="text-white font-bold text-center">Categorie</TableHead>
                            <TableHead className="text-white font-bold text-right">Aantal</TableHead>
                          </TableRow>
                        </TableHeader>
                        <TableBody>
                          {stickerPackage.items.map((item, idx) => {
                            const product = availableProducts.find((p) => p.id === item.productId);
                            return (
                              <TableRow key={item.productId} className={idx % 2 === 0 ? 'bg-white' : 'bg-gray-50'}>
                                <TableCell className="font-medium">{product?.name}</TableCell>
                                <TableCell className="text-center text-sm text-gray-600">{product?.category}</TableCell>
                                <TableCell className="text-right">
                                  <Badge variant="secondary" className="font-bold">{item.quantity}x</Badge>
                                </TableCell>
                              </TableRow>
                            );
                          })}
                        </TableBody>
                      </Table>
                    </div>
                  </div>

                  {/* Footer */}
                  <div className="border-t-2 border-gray-900 pt-3 mt-4">
                    <p className="text-xs text-center text-gray-600">
                      Voedselbank Maaskantje • Dorpsstraat 1, 5741 AB Maaskantje • Tel: 0492-123456
                    </p>
                  </div>
                </div>

                {/* Print Button */}
                <div className="flex gap-2 print:hidden">
                  <Button
                    className="flex-1 gap-2"
                    onClick={() => window.print()}
                  >
                    <Tag className="h-4 w-4" />
                    Sticker Afdrukken
                  </Button>
                  <Button
                    variant="outline"
                    onClick={() => setIsStickerDialogOpen(false)}
                  >
                    Sluiten
                  </Button>
                </div>
              </div>
            );
          })()}
        </DialogContent>
      </Dialog>
    </div>
  );
}