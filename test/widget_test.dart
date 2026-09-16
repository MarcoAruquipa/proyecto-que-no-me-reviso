import 'package:flutter_test/flutter_test.dart';

import 'package:app_frontend/main.dart';

void main() {
  testWidgets('App builds', (WidgetTester tester) async {
    await tester.pumpWidget(const MaletinApp());
    expect(find.byType(MaletinApp), findsOneWidget);
  });
}