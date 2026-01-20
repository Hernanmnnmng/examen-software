import { NavLink } from 'react-router-dom';
import { Package, Truck, ShoppingBag, Menu } from 'lucide-react';
import { useState } from 'react';
import { Button } from '@/app/components/ui/button';
import { Sheet, SheetContent, SheetTrigger } from '@/app/components/ui/sheet';

export function Navigation() {
  const [isOpen, setIsOpen] = useState(false);
  
  const navItems = [
    { path: '/voorraad', label: 'Voorraad', icon: Package },
    { path: '/leveranciers', label: 'Leveranciers', icon: Truck },
    { path: '/voedselpakket', label: 'Voedselpakket', icon: ShoppingBag },
  ];

  return (
    <nav className="bg-white shadow-md">
      <div className="container mx-auto px-4">
        <div className="flex items-center justify-between h-16">
          <div className="flex items-center gap-2">
            <ShoppingBag className="h-6 w-6 sm:h-8 sm:w-8 text-green-600" />
            <h1 className="text-base sm:text-xl font-bold text-gray-900">
              <span className="hidden sm:inline">Voedselbank Maaskantje</span>
              <span className="sm:hidden">Voedselbank</span>
            </h1>
          </div>
          
          {/* Desktop Navigation */}
          <div className="hidden md:flex gap-1">
            {navItems.map(({ path, label, icon: Icon }) => (
              <NavLink
                key={path}
                to={path}
                className={({ isActive }) =>
                  `flex items-center gap-2 px-4 py-2 rounded-md transition-colors ${
                    isActive
                      ? 'bg-green-600 text-white'
                      : 'text-gray-700 hover:bg-gray-100'
                  }`
                }
              >
                <Icon className="h-5 w-5" />
                <span>{label}</span>
              </NavLink>
            ))}
          </div>

          {/* Mobile Navigation */}
          <Sheet open={isOpen} onOpenChange={setIsOpen}>
            <SheetTrigger asChild className="md:hidden">
              <Button variant="ghost" size="sm">
                <Menu className="h-5 w-5" />
              </Button>
            </SheetTrigger>
            <SheetContent side="right" className="w-64">
              <div className="flex flex-col gap-2 mt-8">
                {navItems.map(({ path, label, icon: Icon }) => (
                  <NavLink
                    key={path}
                    to={path}
                    onClick={() => setIsOpen(false)}
                    className={({ isActive }) =>
                      `flex items-center gap-3 px-4 py-3 rounded-md transition-colors ${
                        isActive
                          ? 'bg-green-600 text-white'
                          : 'text-gray-700 hover:bg-gray-100'
                      }`
                    }
                  >
                    <Icon className="h-5 w-5" />
                    <span>{label}</span>
                  </NavLink>
                ))}
              </div>
            </SheetContent>
          </Sheet>
        </div>
      </div>
    </nav>
  );
}