export enum UserRole {
  HOTEL_OWNER = 'HOTEL_OWNER',
  SUPER_ADMIN = 'SUPER_ADMIN'
}

export interface Role {
  id: string;
  name: UserRole;
  permissions: Permission[];
}

export interface Permission {
  id: string;
  name: string;
  description: string;
  scope: 'hotel' | 'system';
}

export interface RoleAssignment {
  userId: string;
  roleId: string;
  hotelIds?: string[]; // Only for HOTEL_OWNER role
}

// Default permissions for each role
export const HOTEL_OWNER_PERMISSIONS: Permission[] = [
  {
    id: 'manage_hotels',
    name: 'Manage Hotels',
    description: 'Create, edit, and delete assigned hotels',
    scope: 'hotel'
  },
  {
    id: 'manage_rooms',
    name: 'Manage Rooms',
    description: 'Manage room types and their details',
    scope: 'hotel'
  },
  {
    id: 'manage_rates',
    name: 'Manage Rates',
    description: 'Manage rate plans and pricing',
    scope: 'hotel'
  }
];

export const SUPER_ADMIN_PERMISSIONS: Permission[] = [
  {
    id: 'manage_all_hotels',
    name: 'Manage All Hotels',
    description: 'Full control over all hotels in the system',
    scope: 'system'
  },
  {
    id: 'manage_users',
    name: 'Manage Users',
    description: 'Manage all users and their roles',
    scope: 'system'
  },
  {
    id: 'manage_system',
    name: 'Manage System',
    description: 'Configure system-wide settings',
    scope: 'system'
  }
];
