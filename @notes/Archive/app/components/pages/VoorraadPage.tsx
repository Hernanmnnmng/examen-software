import { useState } from 'react';
import { Search, Plus, Edit, Package, ArrowUpDown, Trash2 } from 'lucide-react';
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
import { Badge } from '@/app/components/ui/badge';
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
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
import { Label } from '@/app/components/ui/label';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/app/components/ui/select';

// Mock data
const initialProducts = [
  { id: 1, ean: '8712345678901', name: 'Melk Vol 1L', category: 'Zuivel, plantaardig en eieren', quantity: 45 },
  { id: 2, ean: '8712345678902', name: 'Volkoren Brood', category: 'Bakkerij en banket', quantity: 28 },
  { id: 3, ean: '8712345678903', name: 'Aardappelen 1kg', category: 'Aardappelen, groente, fruit', quantity: 62 },
  { id: 4, ean: '8712345678904', name: 'Kip Filet 500g', category: 'Kaas, vleeswaren', quantity: 15 },
  { id: 5, ean: '8712345678905', name: 'Pasta Penne 500g', category: 'Pasta, rijst en wereldkeuken', quantity: 88 },
  { id: 6, ean: '8712345678906', name: 'Appelsap 1L', category: 'Frisdrank, sappen, koffie en thee', quantity: 34 },
  { id: 7, ean: '8712345678907', name: 'Tomatensoep blik', category: 'Soepen, sauzen, kruiden en olie', quantity: 52 },
  { id: 8, ean: '8712345678908', name: 'Koekjes Choco', category: 'Snoep, koek, chips en chocolade', quantity: 19 },
  { id: 9, ean: '8712345678909', name: 'Babyvoeding 6m+', category: 'Baby, verzorging en hygiëne', quantity: 23 },
];

const categories = [
  'Aardappelen, groente, fruit',
  'Kaas, vleeswaren',
  'Zuivel, plantaardig en eieren',
  'Bakkerij en banket',
  'Frisdrank, sappen, koffie en thee',
  'Pasta, rijst en wereldkeuken',
  'Soepen, sauzen, kruiden en olie',
  'Snoep, koek, chips en chocolade',
  'Baby, verzorging en hygiëne',
];

export default function VoorraadPage() {
  const [products, setProducts] = useState(initialProducts);
  const [searchTerm, setSearchTerm] = useState('');
  const [sortConfig, setSortConfig] = useState({ key: '', direction: 'asc' });
  const [isAddDialogOpen, setIsAddDialogOpen] = useState(false);
  const [isEditDialogOpen, setIsEditDialogOpen] = useState(false);
  const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState(false);
  const [editingProduct, setEditingProduct] = useState<typeof initialProducts[0] | null>(null);
  const [deletingProduct, setDeletingProduct] = useState<typeof initialProducts[0] | null>(null);
  const [editForm, setEditForm] = useState({
    ean: '',
    name: '',
    category: '',
    quantity: 0,
  });

  const filteredProducts = products.filter(
    (product) =>
      product.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
      product.ean.includes(searchTerm) ||
      product.category.toLowerCase().includes(searchTerm.toLowerCase())
  );

  const handleSort = (key: string) => {
    setSortConfig({
      key,
      direction: sortConfig.key === key && sortConfig.direction === 'asc' ? 'desc' : 'asc',
    });
  };

  const getStockBadgeVariant = (quantity: number) => {
    if (quantity < 20) return 'destructive';
    if (quantity < 40) return 'default';
    return 'secondary';
  };

  const handleEditClick = (product: typeof initialProducts[0]) => {
    setEditingProduct(product);
    setEditForm({
      ean: product.ean,
      name: product.name,
      category: product.category,
      quantity: product.quantity,
    });
    setIsEditDialogOpen(true);
  };

  const handleSaveEdit = () => {
    if (editingProduct) {
      setProducts(
        products.map((p) =>
          p.id === editingProduct.id
            ? {
                ...p,
                ean: editForm.ean,
                name: editForm.name,
                category: editForm.category,
                quantity: editForm.quantity,
              }
            : p
        )
      );
      setIsEditDialogOpen(false);
      setEditingProduct(null);
    }
  };

  const handleDeleteClick = (product: typeof initialProducts[0]) => {
    setDeletingProduct(product);
    setIsDeleteDialogOpen(true);
  };

  const handleConfirmDelete = () => {
    if (deletingProduct) {
      setProducts(products.filter((p) => p.id !== deletingProduct.id));
      setIsDeleteDialogOpen(false);
      setDeletingProduct(null);
    }
  };

  return (
    <div className="space-y-6">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl sm:text-3xl font-bold text-gray-900">Voorraadbeheer</h1>
          <p className="text-sm sm:text-base text-gray-600 mt-1">Beheer magazijnvoorraad en productinformatie</p>
        </div>
        <Dialog open={isAddDialogOpen} onOpenChange={setIsAddDialogOpen}>
          <DialogTrigger asChild>
            <Button className="gap-2 w-full sm:w-auto">
              <Plus className="h-4 w-4" />
              Nieuw Product
            </Button>
          </DialogTrigger>
          <DialogContent className="w-[95vw] max-w-md">
            <DialogHeader>
              <DialogTitle>Nieuw Product Toevoegen</DialogTitle>
            </DialogHeader>
            <div className="grid gap-4 py-4">
              <div className="grid gap-2">
                <Label htmlFor="ean">EAN Nummer</Label>
                <Input id="ean" placeholder="8712345678901" />
              </div>
              <div className="grid gap-2">
                <Label htmlFor="productname">Productnaam</Label>
                <Input id="productname" placeholder="Bijv. Melk Vol 1L" />
              </div>
              <div className="grid gap-2">
                <Label htmlFor="category">Categorie</Label>
                <Select>
                  <SelectTrigger id="category">
                    <SelectValue placeholder="Selecteer categorie" />
                  </SelectTrigger>
                  <SelectContent>
                    {categories.map((cat) => (
                      <SelectItem key={cat} value={cat}>
                        {cat}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>
              <div className="grid gap-2">
                <Label htmlFor="quantity">Aantal in Voorraad</Label>
                <Input id="quantity" type="number" placeholder="0" />
              </div>
            </div>
            <div className="flex justify-end gap-2">
              <Button variant="outline" onClick={() => setIsAddDialogOpen(false)}>
                Annuleren
              </Button>
              <Button onClick={() => setIsAddDialogOpen(false)}>Opslaan</Button>
            </div>
          </DialogContent>
        </Dialog>
      </div>

      <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div className="flex flex-col sm:flex-row sm:items-center gap-4 mb-6">
          <div className="relative flex-1">
            <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 h-4 w-4" />
            <Input
              placeholder="Zoek op EAN, productnaam of categorie..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="pl-10"
            />
          </div>
          <div className="flex items-center gap-2 text-sm text-gray-600">
            <Package className="h-4 w-4" />
            <span>{filteredProducts.length} producten</span>
          </div>
        </div>

        {/* Desktop Table View */}
        <div className="hidden md:block border rounded-lg overflow-hidden">
          <Table>
            <TableHeader>
              <TableRow className="bg-gray-50">
                <TableHead className="cursor-pointer" onClick={() => handleSort('ean')}>
                  <div className="flex items-center gap-2">
                    EAN Nummer
                    <ArrowUpDown className="h-4 w-4" />
                  </div>
                </TableHead>
                <TableHead className="cursor-pointer" onClick={() => handleSort('name')}>
                  <div className="flex items-center gap-2">
                    Productnaam
                    <ArrowUpDown className="h-4 w-4" />
                  </div>
                </TableHead>
                <TableHead className="cursor-pointer" onClick={() => handleSort('category')}>
                  <div className="flex items-center gap-2">
                    Categorie
                    <ArrowUpDown className="h-4 w-4" />
                  </div>
                </TableHead>
                <TableHead className="cursor-pointer" onClick={() => handleSort('quantity')}>
                  <div className="flex items-center gap-2">
                    Aantal
                    <ArrowUpDown className="h-4 w-4" />
                  </div>
                </TableHead>
                <TableHead className="text-right">Acties</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {filteredProducts.length === 0 ? (
                <TableRow>
                  <TableCell colSpan={5} className="text-center text-gray-500 py-8">
                    Geen producten gevonden
                  </TableCell>
                </TableRow>
              ) : (
                filteredProducts.map((product) => (
                  <TableRow key={product.id}>
                    <TableCell className="font-mono text-sm">{product.ean}</TableCell>
                    <TableCell className="font-medium">{product.name}</TableCell>
                    <TableCell className="text-sm text-gray-600">{product.category}</TableCell>
                    <TableCell>
                      <Badge variant={getStockBadgeVariant(product.quantity)}>
                        {product.quantity} stuks
                      </Badge>
                    </TableCell>
                    <TableCell className="text-right">
                      <Button
                        variant="ghost"
                        size="sm"
                        className="gap-2"
                        onClick={() => handleEditClick(product)}
                      >
                        <Edit className="h-4 w-4" />
                        Wijzigen
                      </Button>
                      <Button
                        variant="ghost"
                        size="sm"
                        className="gap-2"
                        onClick={() => handleDeleteClick(product)}
                      >
                        <Trash2 className="h-4 w-4" />
                        Verwijderen
                      </Button>
                    </TableCell>
                  </TableRow>
                ))
              )}
            </TableBody>
          </Table>
        </div>

        {/* Mobile Card View */}
        <div className="md:hidden space-y-3">
          {filteredProducts.length === 0 ? (
            <div className="text-center text-gray-500 py-8">
              Geen producten gevonden
            </div>
          ) : (
            filteredProducts.map((product) => (
              <div key={product.id} className="border rounded-lg p-4 space-y-3">
                <div className="flex items-start justify-between">
                  <div className="flex-1">
                    <h3 className="font-medium text-gray-900">{product.name}</h3>
                    <p className="text-sm text-gray-600 mt-1">{product.category}</p>
                    <p className="text-xs font-mono text-gray-500 mt-1">{product.ean}</p>
                  </div>
                  <Badge variant={getStockBadgeVariant(product.quantity)}>
                    {product.quantity}
                  </Badge>
                </div>
                <Button
                  variant="outline"
                  size="sm"
                  className="w-full gap-2"
                  onClick={() => handleEditClick(product)}
                >
                  <Edit className="h-4 w-4" />
                  Wijzigen
                </Button>
                <Button
                  variant="outline"
                  size="sm"
                  className="w-full gap-2"
                  onClick={() => handleDeleteClick(product)}
                >
                  <Trash2 className="h-4 w-4" />
                  Verwijderen
                </Button>
              </div>
            ))
          )}
        </div>
      </div>

      {/* Edit Dialog */}
      <Dialog open={isEditDialogOpen} onOpenChange={setIsEditDialogOpen}>
        <DialogContent className="w-[95vw] max-w-md">
          <DialogHeader>
            <DialogTitle>Product Wijzigen</DialogTitle>
          </DialogHeader>
          <div className="grid gap-4 py-4">
            <div className="grid gap-2">
              <Label htmlFor="ean">EAN Nummer</Label>
              <Input
                id="ean"
                placeholder="8712345678901"
                value={editForm.ean}
                onChange={(e) => setEditForm({ ...editForm, ean: e.target.value })}
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="productname">Productnaam</Label>
              <Input
                id="productname"
                placeholder="Bijv. Melk Vol 1L"
                value={editForm.name}
                onChange={(e) => setEditForm({ ...editForm, name: e.target.value })}
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="category">Categorie</Label>
              <Select
                value={editForm.category}
                onValueChange={(value) => setEditForm({ ...editForm, category: value })}
              >
                <SelectTrigger id="category">
                  <SelectValue placeholder="Selecteer categorie" />
                </SelectTrigger>
                <SelectContent>
                  {categories.map((cat) => (
                    <SelectItem key={cat} value={cat}>
                      {cat}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            <div className="grid gap-2">
              <Label htmlFor="quantity">Aantal in Voorraad</Label>
              <Input
                id="quantity"
                type="number"
                placeholder="0"
                value={editForm.quantity.toString()}
                onChange={(e) => setEditForm({ ...editForm, quantity: parseInt(e.target.value) })}
              />
            </div>
          </div>
          <div className="flex justify-end gap-2">
            <Button variant="outline" onClick={() => setIsEditDialogOpen(false)}>
              Annuleren
            </Button>
            <Button onClick={handleSaveEdit}>Opslaan</Button>
          </div>
        </DialogContent>
      </Dialog>

      {/* Delete Dialog */}
      <AlertDialog open={isDeleteDialogOpen} onOpenChange={setIsDeleteDialogOpen}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Product Verwijderen</AlertDialogTitle>
            <AlertDialogDescription>
              Weet je zeker dat je dit product wilt verwijderen? Deze actie kan niet ongedaan worden gemaakt.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel onClick={() => setIsDeleteDialogOpen(false)}>
              Annuleren
            </AlertDialogCancel>
            <AlertDialogAction onClick={handleConfirmDelete}>
              Verwijderen
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </div>
  );
}