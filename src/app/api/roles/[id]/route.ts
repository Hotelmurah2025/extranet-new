import { NextResponse } from 'next/server';
import type { Role } from '../../../../types/role';

// Mock data store - replace with actual database in production
let roles: Role[] = [
  // ... roles will be populated from the main roles route
];

// GET /api/roles/[id] - Get a specific role
export async function GET(
  request: Request,
  { params }: { params: { id: string } }
) {
  try {
    const role = roles.find(r => r.id === params.id);
    if (!role) {
      return NextResponse.json(
        { error: 'Role not found' },
        { status: 404 }
      );
    }
    return NextResponse.json(role);
  } catch (error) {
    return NextResponse.json(
      { error: 'Failed to fetch role' },
      { status: 500 }
    );
  }
}

// PUT /api/roles/[id] - Update a role
export async function PUT(
  request: Request,
  { params }: { params: { id: string } }
) {
  try {
    const body = await request.json();
    const { name, permissions } = body;

    const roleIndex = roles.findIndex(r => r.id === params.id);
    if (roleIndex === -1) {
      return NextResponse.json(
        { error: 'Role not found' },
        { status: 404 }
      );
    }

    // Update role
    roles[roleIndex] = {
      ...roles[roleIndex],
      name,
      permissions
    };

    return NextResponse.json(roles[roleIndex]);
  } catch (error) {
    return NextResponse.json(
      { error: 'Failed to update role' },
      { status: 500 }
    );
  }
}

// DELETE /api/roles/[id] - Delete a role
export async function DELETE(
  request: Request,
  { params }: { params: { id: string } }
) {
  try {
    const roleIndex = roles.findIndex(r => r.id === params.id);
    if (roleIndex === -1) {
      return NextResponse.json(
        { error: 'Role not found' },
        { status: 404 }
      );
    }

    roles.splice(roleIndex, 1);
    return NextResponse.json({ message: 'Role deleted successfully' });
  } catch (error) {
    return NextResponse.json(
      { error: 'Failed to delete role' },
      { status: 500 }
    );
  }
}
